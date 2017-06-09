<?php declare(strict_types=1);

namespace KoldyAdmin;

use Koldy\Application;

final class Config
{

    /**
     * @return \Koldy\Config
     */
    public static function getConfig(): \Koldy\Config
    {
        return Application::getConfig('koldy-admin');
    }

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        return self::getConfig()->get('version', '0.0.1');
    }

    /**
     * @return string|null
     */
    public static function getHash(): ?string
    {
        return self::getConfig()->get('hash');
    }

    /**
     * @param null|string $add
     *
     * @return string
     */
    public static function getSiteTitle(?string $add = null): string
    {
        $title = self::getConfig()->get('site_title', 'Koldy Admin');

        if ($add !== null) {
            $title = "{$add} | {$title}";
        }

        return $title;
    }

    /**
     * Get no-reply email from koldy-admin config
     *
     * @return string
     */
    public static function noReplyEmail(): string
    {
        return self::getConfig()->get('no-reply-email') ?? 'no-reply@' . Application::getDomain();
    }

    /**
     * @return string
     * @example en_AU
     */
    public static function getLocale(): string
    {
        return self::getConfig()->get('locale', 'en_US');
    }

    /**
     * @return string
     * @example en
     */
    public static function getLanguage(): string
    {
        return self::getConfig()->get('language', 'en');
    }

    /**
     * Get the company name from config
     *
     * @return string
     */
    public static function getCompany(): string
    {
        return self::getConfig()->get('company');
    }
}