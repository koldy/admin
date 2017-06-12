<?php declare(strict_types=1);

namespace KoldyAdmin\Db;

use DateTime;
use JsonSerializable;
use Koldy\Application;
use Koldy\Db\Model;
use Koldy\Db\Where;
use Koldy\Request;
use KoldyAdmin\Config;
use KoldyAdmin\Db\Traits\AccountTrait;
use KoldyAdmin\Exception;

/**
 * Class AdminLoginHistory
 * @package KoldyAdmin\Db
 * @property int id
 * @property int account_id
 * @property string request_at
 * @property int is_success
 * @property string uas
 * @property string ip
 * @property string proxy_ip
 * @property int screen_width
 * @property int screen_height
 */
class AdminLoginHistory extends Model implements JsonSerializable
{

    protected static $table = 'admin_login_history';

    protected static $adapter = 'admin';

    use AccountTrait;

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @return string
     */
    public function getRequestAt(): string
    {
        return $this->request_at;
    }

    /**
     * @return DateTime
     */
    public function getRequestAtDateTime(): DateTime
    {
        return new DateTime($this->request_at);
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return (int)$this->is_success == 1;
    }

    /**
     * @return string
     */
    public function getUas(): string
    {
        return $this->uas;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getProxyIp(): ?string
    {
        return $this->proxy_ip;
    }

    /**
     * @return int
     */
    public function getScreenWidth(): int
    {
        return (int)$this->screen_width;
    }

    /**
     * @return int
     */
    public function getScreenHeight(): int
    {
        return (int)$this->screen_height;
    }

    /**
     * @param AdminAccount $account
     * @param bool $isSuccess
     * @param int|null $screenWidth
     * @param int|null $screenHeight
     *
     * @return AdminLoginHistory
     * @throws Exception
     */
    public static function createFromRequestFor(
      AdminAccount $account,
      bool $isSuccess,
      int $screenWidth = null,
      int $screenHeight = null
    ): self {
        if (Application::isCli()) {
            throw new Exception('Can not run createFromRequestFor() in CLI env');
        }

        $ip = Request::ip();
        $proxyIp = Request::ipWithProxy();

        if ($proxyIp == $ip) {
            $proxyIp = null;
        }

        $config = Config::getConfig();
        $maxRecordsPerUser = $config->has('users') ? $config->getArrayItem('users', 'max_sign_in_records_per_user', 100) : 100;
        $record = static::select()->field('id')->field('request_at')->limit($maxRecordsPerUser - 1, 1)->orderBy('id', 'desc')->fetchFirstObj();

        if ($record !== null) {
            static::delete(Where::init()
              ->where('account_id', $account->getId())
              ->where('id', '<=', $record->id));
        }

        /** @var AdminLoginHistory $self */
        $self = static::create([
          'account_id' => $account->getId(),
          'request_at' => gmdate('Y-m-d H:i:s'),
          'is_success' => $isSuccess ? 1 : 0,
          'uas' => Request::userAgent(),
          'ip' => $ip,
          'proxy_ip' => $proxyIp,
          'screen_width' => $screenWidth,
          'screen_height' => $screenHeight
        ]);

        return $self;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
          'id' => $this->getId(),
          'request_at' => $this->getRequestAt(),
          'is_success' => $this->isSuccess(),
          'uas' => $this->getUas(),
          'ip' => $this->getIp(),
          'screen_width' => $this->getScreenWidth(),
          'screen_height' => $this->getScreenHeight()
        ];
    }

    public function __toString()
    {
        return "AdminLoginHistory #{$this->getId()} account_id={$this->getAccountId()}";
    }
}