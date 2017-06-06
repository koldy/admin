<?php declare(strict_types=1);

namespace KoldyAdmin\AdminAccount;

use KoldyAdmin\AdminAccount\Meta\Security;
use KoldyAdmin\Db\AdminAccount;
use KoldyAdmin\Db\AdminAccountMeta;
use Koldy\Db\Exception as DbException;

class Meta
{

    /**
     * @var AdminAccount
     */
    private $adminAccount;

    /**
     * @var AdminAccountMeta[]
     */
    private $metas = null;

    /**
     * Meta constructor.
     *
     * @param AdminAccount $adminAccount
     * @param bool $load
     */
    public function __construct(AdminAccount &$adminAccount, bool $load = true)
    {
        $this->adminAccount = $adminAccount;

        if ($this->metas === null && $load) {
            $this->load();
        }
    }

    /**
     * @return Meta
     */
    public function load(): self
    {
        /** @var AdminAccountMeta[] metas */
        $metas = AdminAccountMeta::fetch(['account_id' => $this->getAdminAccount()->getId()]);

        $this->metas = [];
        foreach ($metas as $meta) {
            /** @var $meta AdminAccountMeta */
            $this->metas[$meta->getKey()] = $meta;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isLoaded(): bool
    {
        return $this->metas !== null;
    }

    /**
     * @return AdminAccountMeta[]|null
     */
    public function getMetas(): ?array
    {
        return $this->metas;
    }

    /**
     * @return AdminAccount
     */
    public function getAdminAccount(): AdminAccount
    {
        return $this->adminAccount;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        if ($this->metas === null) {
            return false;
        }

        return array_key_exists($key, $this->metas);
    }

    /**
     * @param string $key
     *
     * @return AdminAccountMeta|null
     */
    public function get(string $key): ?AdminAccountMeta
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->metas[$key];
    }

    /**
     * @param array ...$keys
     *
     * @return array
     */
    public function getMetasForKeys(...$keys): array
    {
        $allMeta = [];
        foreach ($keys as $key) {
            $meta = $this->get($key);
            if ($meta !== null) {
                $allMeta[] = $meta;
            }
        }

        return $allMeta;
    }

    /**
     * @param string $key
     * @param $value
     *
     * @return AdminAccountMeta
     */
    public function set(string $key, $value): AdminAccountMeta
    {
        if ($this->isLoaded()) {

            $meta = $this->get($key);

            if ($meta === null) {
                /** @var AdminAccountMeta $meta */
                $this->metas[$key] = AdminAccountMeta::create([
                  'account_id' => $this->adminAccount->getId(),
                  'key_name' => $key,
                  'key_value' => $value,
                  'created_at' => gmdate('Y-m-d H:i:s')
                ]);

            } else {
                $this->metas[$key]->key_value = $value;
                $this->metas[$key]->updated_at = gmdate('Y-m-d H:i:s');
                $this->metas[$key]->save();
            }

            return $this->metas[$key];

        } else {
            try {
                /** @var AdminAccountMeta $meta */
                $meta = AdminAccountMeta::create([
                  'account_id' => $this->adminAccount->getId(),
                  'key_name' => $key,
                  'key_value' => $value,
                  'created_at' => gmdate('Y-m-d H:i:s')
                ]);
            } catch (DbException $e) {
                /** @var AdminAccountMeta $meta */
                $meta = AdminAccountMeta::fetchOneOrFail([
                  'account_id' => $this->adminAccount->getId(),
                  'key_name' => $key
                ]);

                $meta->key_value = $value;
                $meta->updated_at = gmdate('Y-m-d H:i:s');
                $meta->save();
            }

            return $meta;
        }
    }

    /**
     * @return Security
     */
    public function security(): Security
    {
        return new Security($this);
    }
}