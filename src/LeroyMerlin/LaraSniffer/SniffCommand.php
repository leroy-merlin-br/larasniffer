<?php namespace LeroyMerlin\LaraSniffer;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * SniffCommand is an artisan command (meant to be used with Laravel) that
 * detects violations of a defined set of coding standards using PHP_CodeSniffer
 *
 * @author    Zizaco Zizuini <zizaco@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @link      https://github.com/leroy-merlin-br/larasniffer
 */
class SniffCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sniff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect violations of coding standard.';

    /**
     * Array of possible shell colors
     * @var array
     */
    private $colors = array(
        'black'        => '0;30',
        'dark_gray'    => '1;30',
        'blue'         => '0;34',
        'light_blue'   => '1;34',
        'green'        => '0;32',
        'light_green'  => '1;32',
        'cyan'         => '0;36',
        'light_cyan'   => '1;36',
        'red'          => '0;31',
        'light_red'    => '1;31',
        'purple'       => '0;35',
        'light_purple' => '1;35',
        'brown'        => '0;33',
        'yellow'       => '1;33',
        'light_gray'   => '0;37',
        'white'        => '1;37',
    );

    /**
     * The exit code of the command
     * @var integer
     */
    protected $exitCode = 0;

    /**
     * The Laravel application instance.
     *
     * @return \Illuminate\Foundation\Application
     */
    public $app = null;

    /**
     * The Laravel Config component
     *
     * @return \Illuminate\Config\Repository
     */
    public $config = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->app    = $this->getLaravel();
        $this->config = $this->app->make('config');

        $output = $this->runSniffer();

        if ($this->terminalHasColorSupport()) {
            foreach (explode("\n", $output) as $line) {
                echo $this->formatLine($line)."\n";
            }
        } else {
            echo $output;
        }

        $this->terminate($this->exitCode);
    }

    /**
     * Include, instantiate and run PHP_CodeSniffer
     * @return string Text output
     */
    public function runSniffer()
    {
        $phpcs = $this->getPHPSnifferInstance();
        $phpcs->checkRequirements();

        $standard = $this->config->get('larasniffer::standard', array('PSR2'));
        $files    = $this->config->get('larasniffer::files', array('app/models', 'app/controllers'));

        $options = array(
            'standard' => $standard,
            'files'    => $files
        );

        ob_start();
        $numErrors = $phpcs->process($options);
        $output = ob_get_contents();
        ob_end_clean();

        if ($numErrors != 0) {
            $this->exitCode = 1;
        }

        return $output;
    }

    /**
     * Adds color to a line before printing in the terminal.
     * @param  string $text Output line to be formated
     * @return string Formated line
     */
    protected function formatLine($text)
    {
        $text = str_replace('|', $this->colorize('dark_gray', '|'), $text);

        if (strstr($text, '---------')) {
            return $this->colorize('dark_gray', $text);
        } elseif (strstr($text, 'FILE: ')) {
            return $this->colorize('light_green', $text);
        } elseif (strstr($text, 'ERROR(S)')) {
            return $this->colorize('light_red', $text);
        } elseif (strstr($text, 'ERROR')) {
            return str_replace('ERROR', $this->colorize('light_red', 'ERROR'), $text);
        } elseif (strstr($text, 'WARNING')) {
            return str_replace('WARNING', $this->colorize('yellow', 'WARNING'), $text);
        } else {
            return $text;
        }
    }

    /**
     * Returns colored output
     * @param  string $color  Color name
     * @param  string $string Text
     * @return void
     */
    protected function colorize($color, $string)
    {
        return
            "\033[" . $this->colors[$color] . "m".
            $string.
            "\033[0m";
    }

    /**
     * Include the CodeSniffer file (since there is not a better way to load it)
     * and then instantiate the object using the IoC container.
     *
     * @codeCoverageIgnore
     * @return \PHP_CodeSniffer_CLI A class to process command line phpcs scripts.
     * @see https://github.com/squizlabs/PHP_CodeSniffer
     */
    protected function getPHPSnifferInstance()
    {
        include app_path().'/../vendor/squizlabs/php_codesniffer/CodeSniffer/CLI.php';

        return $this->getLaravel()->make("PHP_CodeSniffer_CLI");
    }

    /**
     * Returns true if the stream supports colorization. Colorization is
     * disabled if not supported by the stream:
     *  -  Windows without Ansicon and ConEmu
     *  -  non tty consoles
     *
     * @codeCoverageIgnore
     * @return boolean
     */
    protected function terminalHasColorSupport()
    {
        if (DIRECTORY_SEPARATOR == '\\') {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI');
        }

        return function_exists('posix_isatty');
    }

    /**
     * Exits the command
     *
     * @codeCoverageIgnore
     * @param  mixed $code The exit code
     * @return void
     */
    protected function terminate($code)
    {
        exit($code);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }
}
