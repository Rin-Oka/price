<?php
namespace App\Shell;

use App\Model\Kumaneko\AmaproClient;
use App\Model\Kumaneko\Price;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Exception;
use Psr\Log\LogLevel;

/**
 * Price shell command.
 */
class PriceShell extends Shell
{
    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return void Success or error code.
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
    }

    /**
     * @param string $asin
     */
    public function getPrice($asin)
    {
        try {
            $amaproClient = new AmaproClient();
            $productInfos = $amaproClient->exec([$asin]);
            if (empty($productInfos)) {
                throw new Exception('商品情報が取得できませんでした。');
            }
            $productInfo = $productInfos[0];
            print_r(Price::main($productInfo));
        } catch (Exception $e) {
            $this->log($e, LogLevel::ERROR);
        }

    }
}
