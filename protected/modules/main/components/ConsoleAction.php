<?
/**
 * save one attribute of one model
 * if $_POST['bulk_update'] == true then make bulk update for many models, but only one attribute of one model
 */
class ConsoleAction extends CAction
{
    public function run()
    {
        $command = trim($_POST['command']);
        $base_len = strpos($command, ' ');
        try
        {
            if ($base_len)
            {
                $base = substr($command, 0, $base_len);
                echo Yii::app()->executor->$base(trim(substr($command, $base_len)));
            }
            else
            {
                echo Yii::app()->executor->$command();
            }

        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }

}