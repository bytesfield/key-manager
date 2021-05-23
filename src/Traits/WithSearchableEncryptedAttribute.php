<?php

namespace Bytesfield\KeyManager\Traits;

use Illuminate\Database\Eloquent\Builder;
use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\CipherSweet;
use ParagonIE\CipherSweet\EncryptedField;

trait WithSearchableEncryptedAttribute
{
    protected EncryptedField $encryptedField;

    /**
     * Initialize the trait by setting up the searchable encryption algorithm.
     *
     * @throws \ParagonIE\CipherSweet\Exception\BlindIndexNameCollisionException
     * @throws \ParagonIE\CipherSweet\Exception\CryptoOperationException
     *
     * @return void
     */
    protected function initializeWithSearchableEncryptedAttribute(): void
    {
        $encryptedField = new EncryptedField(
            app(CipherSweet::class),
            $this->getTable(),
            $this->getEncryptedColumn()
        );

        $this->encryptedField = $encryptedField->addBlindIndex(
            new BlindIndex($this->getBlindIndexName(), [], 256, true)
        );
    }

    /**
     * Encrypts a value.
     *
     * @param string $value
     *
     * @return string
     */
    protected function encrypt(string $value): string
    {
        return $this->encryptedField->encryptValue($value);
    }

    /**
     * Decrypts a value.
     *
     * @param string $value
     *
     * @return string
     */
    protected function decrypt(string $value): string
    {
        return $this->encryptedField->decryptValue($value);
    }

    /**
     * Generates a build index hash using the value of the encrypted column.
     *
     * @throws \ParagonIE\CipherSweet\Exception\BlindIndexNotFoundException
     * @throws \ParagonIE\CipherSweet\Exception\CryptoOperationException
     * @throws \SodiumException
     *
     * @return string
     */
    protected function blindIndexValue(): string
    {
        return $this->getBlindIndexValueFor(
            (string) $this->getAttribute($this->getEncryptedColumn())
        );
    }

    /**
     * Generate a build index hash based on provided string value.
     *
     * @param string $string
     *
     * @throws \ParagonIE\CipherSweet\Exception\BlindIndexNotFoundException
     * @throws \ParagonIE\CipherSweet\Exception\CryptoOperationException
     * @throws \SodiumException
     *
     * @return string
     */
    protected function getBlindIndexValueFor(string $string): string
    {
        return $this->encryptedField->getBlindIndex($string, $this->getBlindIndexName());
    }

    /**
     * Query model by the build index hash of the provided value.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $value
     *
     * @throws \ParagonIE\CipherSweet\Exception\BlindIndexNotFoundException
     * @throws \ParagonIE\CipherSweet\Exception\CryptoOperationException
     * @throws \SodiumException
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereBlindIndex(Builder $query, string $value): Builder
    {
        return $query->where($this->getBlindIndexColumn(), $this->getBlindIndexValueFor($value));
    }

    /**
     * Get the name of the build index. e.g api_keys_encrypted_hash_blind_index.
     *
     * @return string
     */
    protected function getBlindIndexName(): string
    {
        return $this->getTable().'_'.$this->getBlindIndexColumn().'_blind_index';
    }

    /**
     * Get the name of the attribute/column whose value is to be encrypted.
     *
     * @return string
     */
    protected function getEncryptedColumn(): string
    {
        return $this->encrypted ?? 'encrypted';
    }

    /**
     * Get the name of the attribute/column which is to be used to build and access the build index hash.
     *
     * @return string
     */
    protected function getBlindIndexColumn(): string
    {
        return $this->blindIndex ?? 'encrypted_hash';
    }
}
