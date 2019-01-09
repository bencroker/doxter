<?php
namespace selvinortiz\doxter\models;

use craft\base\Model;

use selvinortiz\doxter\common\parsers\Shortcode;

/**
 * Models the properties of a parsed shortcode
 *
 * Class DoxterShortcodeModel
 *
 * @package selvinortiz\doxter\models
 *
 * @property string $name
 * @property array  $params
 * @property string $content
 *
 * @example
 * [image src="/images/image.jpg" alt="My Image"/]
 *
 * {
 *  name: "image",
 *  params: {src: "/images/image.jpg", alt: "My Image"},
 *  content: ""
 * }
 *
 * [updates]
 *  [note type="added"]Added a very cool feature[/note]
 * [/updates]
 *
 * {
 *  name: "updates"
 *  params: {},
 *  content: "[note type=\"added\"]Added a very cool feature[/note]"
 * }
 */
class ShortcodeModel extends Model
{
    /**
     * Shortcode name/abbreviation
     * e.g. [img] 👉 "img"
     *
     * @var string
     */
    public $name;

    /**
     * All key/value pairs found in the shortcode definition
     * e.g. [img src=image.png size=large] 👉 ['src' => 'image.png', 'size' => 'large']
     *
     * @var array
     */
    public $params;

    /**
     * Content between start and end shortcode tags
     * e.g. [quote]Quote text goes here...[/quote]
     *
     * @var string
     */
    public $content;

    /**
     * Returns a parameter value if one is found
     * Useful when accessing via twig
     *
     * @param string $param
     *
     * @return mixed|null
     */
    public function __get($param)
    {
        return $this->getParam($param);
    }

    /**
     * Returns a parsed shortcode parameter value if found or $default value
     *
     * @param string     $name
     * @param null|mixed $default
     *
     * @return null|mixed
     */
    public function getParam($name, $default = null)
    {
        return $this->params[$name] ?? $default;
    }

    /**
     * @return mixed|string
     */
    public function parseContent()
    {
        if (empty($this->content))
        {
            return '';
        }

        if (mb_stripos($this->content, '[') !== false || mb_stripos($this->content, '{') !== false)
        {
            return Shortcode::instance()->parse($this->content);
        }

        return $this->content;
    }
}
