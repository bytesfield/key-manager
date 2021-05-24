<?php

namespace Bytesfield\KeyManager\Models;

use Bytesfield\KeyManager\Traits\WithSearchableEncryptedAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiCredential extends Model
{
    use SoftDeletes;
    use HasFactory;
    use WithSearchableEncryptedAttribute;

    protected $guarded = [];

    protected $table = 'key_api_credentials';

    /**
     * Statues for api credential.
     */
    public const STATUSES = [
        'ACTIVE' => 'active',
        'SUSPENDED' => 'suspended',
    ];

    /**
     * Public key prefix.
     */
    public const PUBLIC_KEY_PREFIX = 'api_key_pub';

    /**
     * Private key prefix.
     */
    public const PRIVATE_KEY_PREFIX = 'api_key_prv';

    /**
     * The hidden columns.
     *
     * @var string[]
     */
    protected $hidden = [
        'secret_hash', 'deleted_at',
    ];

    /**
     * The encrypted field or column.
     *
     * @var string
     */
    protected $encrypted = 'private_key';

    /**
     * The blind index field or column.
     *
     * @var string
     */
    protected $blindIndex = 'secret_hash';

    /**
     * Get the client for the api key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id');
    }

    /**
     * Sets the value for private key column.
     *
     * @param string $key
     *
     * @return void
     */
    public function setPrivateKeyAttribute(string $key): void
    {
        $this->attributes['private_key'] = $this->encrypt($key);
    }

    /**
     * Sets the value secret hash column.
     *
     * @param string|null $hash
     *
     * @throws \ParagonIE\CipherSweet\Exception\BlindIndexNotFoundException
     * @throws \ParagonIE\CipherSweet\Exception\CryptoOperationException
     * @throws \SodiumException
     *
     * @return void
     */
    public function setSecretHashAttribute(?string $hash = null): void
    {
        $this->attributes['secret_hash'] = $this->blindIndexValue();
    }

    /**
     * Get the value for private key column.
     *
     * @param string $encryptedKey
     *
     * @return mixed
     */
    public function getPrivateKeyAttribute(string $encryptedKey)
    {
        return $this->decrypt($encryptedKey);
    }

    /**
     * Add where clause for status equals active.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUSES['ACTIVE']);
    }

    /**
     * Add where clause for status equals active.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuspended(Builder $query): Builder
    {
        return $query->where('status', self::STATUSES['SUSPENDED']);
    }

    /**
     * Find client active api keys by private key.
     *
     * @param string $privateKey
     *
     * @return static|null
     */
    public static function findActiveByPrivateKey(string $privateKey): ?self
    {
        return static::query()
            ->with('client')
            ->active()
            ->whereBlindIndex($privateKey)
            ->first();
    }

    /**
     * Find client suspended api keys by private key.
     *
     * @param string $privateKey
     *
     * @return static|null
     */
    public static function findSuspendedByPrivateKey(string $privateKey): ?self
    {
        return static::query()
            ->with('client')
            ->suspended()
            ->whereBlindIndex($privateKey)
            ->first();
    }

    /**
     * Find client api keys by private key.
     *
     * @param string $privateKey
     *
     * @return static|null
     */
    public static function findByPrivateKey(string $privateKey): ?self
    {
        return self::withTrashed()
            ->with('client')
            ->whereBlindIndex($privateKey)
            ->first();
    }

    /**
     * Generates the public and secret key pairs for both live and test domains.
     * @throws \Exception
     *
     * @return array
     */
    public static function generateKeyPairArray(): array
    {
        [$publicKey, $privateKey] = self::generatePublicPrivateKeys();

        return [
            'public_key' => self::PUBLIC_KEY_PREFIX.'_'.$publicKey,
            'private_key' => self::PRIVATE_KEY_PREFIX.'_'.$privateKey,
            'secret_hash' => null,
            'status' => self::STATUSES['ACTIVE'],
        ];
    }

    /**
     * Generate unique public and secret key pairs.
     * @throws \Exception
     *
     * @return array
     */
    private static function generatePublicPrivateKeys(): array
    {
        do {
            $privateKey = bin2hex(random_bytes(20));
            $publicKey = bin2hex(random_bytes(20));

            $secretHash = (new self())->getBlindIndexValueFor($privateKey);
            $hashExists = self::where('secret_hash', $secretHash)->withTrashed()->exists();
        } while ($hashExists);

        return [$publicKey, $privateKey];
    }
}
