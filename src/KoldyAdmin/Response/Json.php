<?php declare(strict_types=1);

namespace KoldyAdmin\Response;

use KoldyAdmin;
use KoldyAdmin\Exception;

/**
 * Class Json
 * @package koldy-admin\Response
 */
class Json extends \Koldy\Response\Json
{

    /**
     * @param string $state
     *
     * @throws Exception
     */
    protected function checkState(?string $state): void
    {
        if ($state !== null && !KoldyAdmin::isBaseBootstrapColor($state)) {
            $allColors = KoldyAdmin::getBaseBootstrapColors();
            throw new Exception("Invalid state: {$state}; allowed states are: " . implode(', ', $allColors));
        }
    }

    /**
     * @param array $data
     *
     * @return Json
     */
    private function addAlertResponseData(array $data): self
    {
        $alerts = $this->get('alert');

        if (!is_array($alerts)) {
            $alerts = [];
        }

        $alerts[] = $data;
        return $this->set('alert', $alerts);
    }

    /**
     * Alert some message to user
     *
     * @param string $content
     * @param string|null $color
     *
     * @param string $faIcon
     *
     * @return Json
     */
    public function alert(string $content, string $color = null, string $faIcon = null): self
    {
        return $this->addAlertResponseData([
          'type' => 'alert',
          'content' => $content,
          'state' => $color,
          'icon' => $faIcon
        ]);
    }

    /**
     * Alert some message to user
     *
     * @param string $content
     * @param string $redirectTo
     * @param string|null $state
     * @param string|null $faIcon
     *
     * @return Json
     */
    public function alertTextAndRedirect(string $content, string $redirectTo, string $state = null, string $faIcon = null): self
    {
        return $this->addAlertResponseData([
          'type' => 'alert-and-redirect',
          'content' => $content,
          'state' => $state,
          'icon' => $faIcon,
          'location' => $redirectTo
        ]);
    }

    /**
     * Alert some message to user
     *
     * @param string $content
     * @param string $redirectTo
     *
     * @return Json
     */
    public function alertLoadingAndRedirect(string $content, string $redirectTo): self
    {
        return $this->addAlertResponseData([
          'type' => 'alert-loader-and-redirect',
          'content' => $content,
          'location' => $redirectTo
        ]);
    }

    /**
     * Redirect user to some other page
     *
     * @param string $redirectTo
     * @param int|null $afterSeconds
     *
     * @return Json
     */
    public function redirect(string $redirectTo, int $afterSeconds = null): self
    {
        return $this->set('redirect', [
          'location' => $redirectTo,
          'after' => $afterSeconds
        ]);
    }

    /**
     * Show notification ON UI
     *
     * @param string $content
     * @param string|null $title
     * @param string|null $state
     * @param string|null $icon
     * @param int|null $duration put zero to make it "sticky"
     *
     * @return Json
     */
    public function notify(string $content, string $title = null, string $state = null, string $icon = null, int $duration = null): self
    {
        $this->checkState($state);

        $notifications = $this->get('notifications');

        if (!is_array($notifications)) {
            $notifications = [];
        }

        // if value is null, it means that defaults will be taken in Javascript

        $notifications[] = [
          'content' => $content,
          'title' => $title,
          'state' => $state,
          'icon' => $icon,
          'duration' => $duration
        ];

        return $this->set('notifications', $notifications);
    }
}