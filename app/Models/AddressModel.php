<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * AddressModel
 *
 * Pure geographic reference table (Province / City / Barangay only).
 * Street-level detail is stored on the owning record (employees,
 * emergency_contacts, character_references) since it is rarely shared
 * between people, while province/city/barangay genuinely is.
 */
class AddressModel extends Model
{
    protected $table         = 'addresses';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'province',
        'city',
        'barangay',
    ];

    /**
     * Find an existing address row matching province+city+barangay,
     * or create a new one. Returns the address ID, or null if all
     * three parts are empty.
     *
     * @param array $parts ['province' => ..., 'city' => ..., 'barangay' => ...]
     */
    public function findOrCreate(array $parts): ?int
    {
        $province = trim($parts['province'] ?? '');
        $city     = trim($parts['city'] ?? '');
        $barangay = trim($parts['barangay'] ?? '');

        if ($province === '' && $city === '' && $barangay === '') {
            return null;
        }

        $existing = $this->where('province', $province)
            ->where('city', $city)
            ->where('barangay', $barangay)
            ->first();

        if ($existing) {
            return (int) $existing['id'];
        }

        return $this->insert([
            'province' => $province ?: null,
            'city'     => $city ?: null,
            'barangay' => $barangay ?: null,
        ]);
    }

    /**
     * Format a joined address (province/city/barangay) plus a separate
     * street value into a single display string.
     */
    public function formatAddress(?string $street, ?array $address): string
    {
        $parts = array_filter([
            $street,
            $address['barangay'] ?? null,
            $address['city']     ?? null,
            $address['province'] ?? null,
        ]);

        return $parts ? implode(', ', $parts) : '—';
    }
}
