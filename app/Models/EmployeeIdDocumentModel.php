<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * EmployeeIdDocumentModel
 *
 * Stores front/back photo uploads for each government ID type
 * (SSS, PhilHealth, Pag-IBIG, TIN) per employee. One row per
 * employee per ID type, enforced by a unique key.
 */
class EmployeeIdDocumentModel extends Model
{
    protected $table         = 'employee_id_documents';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'employee_id',
        'id_type',
        'photo_front',
        'photo_back',
    ];

    public const ID_TYPES = ['SSS', 'PhilHealth', 'Pag-IBIG', 'TIN'];

    /**
     * Get all ID document rows for an employee, keyed by id_type
     * for easy lookup in views (e.g. $docs['SSS']['photo_front']).
     */
    public function getByEmployee(int $employeeId): array
    {
        $rows = $this->where('employee_id', $employeeId)->findAll();

        $keyed = [];
        foreach (self::ID_TYPES as $type) {
            $keyed[$type] = ['photo_front' => null, 'photo_back' => null, 'id' => null];
        }
        foreach ($rows as $row) {
            $keyed[$row['id_type']] = $row;
        }

        return $keyed;
    }

    /**
     * Insert or update the photo(s) for a specific ID type belonging
     * to an employee. Only overwrites photo_front/photo_back when a
     * new value is explicitly provided, preserving existing photos
     * otherwise.
     */
    public function upsertPhoto(int $employeeId, string $idType, array $photos): void
    {
        $existing = $this->where('employee_id', $employeeId)
            ->where('id_type', $idType)
            ->first();

        $data = array_filter($photos, fn($v) => $v !== null);

        if ($existing) {
            if (! empty($data)) {
                $this->update($existing['id'], $data);
            }
            return;
        }

        if (! empty($data)) {
            $this->insert(array_merge([
                'employee_id' => $employeeId,
                'id_type'     => $idType,
            ], $data));
        }
    }
}
