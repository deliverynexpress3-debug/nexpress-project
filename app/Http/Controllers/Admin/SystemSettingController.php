<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddToCart;
use App\Models\Article;
use App\Models\Attributes;
use App\Models\AttributesOption;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Common;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryBoy;
use App\Models\DeliveryInstructor;
use App\Models\Hashtag;
use App\Models\Items;
use App\Models\ItemsAttributes;
use App\Models\ItemsFaq;
use App\Models\ItemsImages;
use App\Models\Notification;
use App\Models\NotificationTracking;
use App\Models\Offers;
use App\Models\Onboarding_Screen;
use App\Models\OrderCancel;
use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\OrdersTracking;
use App\Models\Page;
use App\Models\Promocode;
use App\Models\Reason;
use App\Models\ReturnRequest;
use App\Models\Review;
use App\Models\Section;
use App\Models\SellerRequest;
use App\Models\Settelment;
use App\Models\Social_Link;
use App\Models\StockStatus;
use App\Models\Taxes;
use App\Models\TrackIntrest;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\Vehicle;
use App\Models\Wishlist;
use App\Models\ZipCode;
use App\Models\Zones;
use App\Models\ZonesValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Exception;

class SystemSettingController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {

            $params['data'] = [];
            return view('admin.system_setting.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function ClearData()
    {
        try {

            $simpleFolders = [
                'database',
                'invoice',
                'withdrawal_receipt'
            ];

            foreach ($simpleFolders as $folder) {
                $this->clearAllFilesFromFolder($folder);
            }

            $folderModelMap = [
                'article'             => [Article::class, ['image']],
                'attributes'          => [Attributes::class, ['icon']],
                'brand'               => [Brand::class, ['logo']],
                'category'            => [Category::class, ['image']],
                'customer'            => [Customer::class, ['image']],
                'delivery_boy'        => [DeliveryBoy::class, ['image']],
                'delivery_instructor' => [DeliveryInstructor::class, ['image']],
                'notification'        => [Notification::class, ['image']],
                'promocode'           => [Promocode::class, ['image']],
                'section'             => [Section::class, ['background_value']],
                'offers'              => [Offers::class, ['image', 'banner_image']],
                'type'                => [Type::class, ['icon', 'banner_image']],
            ];

            foreach ($folderModelMap as $folder => $config) {

                $model = $config[0];
                $columns = $config[1];

                $usedFiles = $model::select($columns)
                    ->get()
                    ->flatMap(function ($row) use ($columns) {
                        return collect($columns)->map(function ($col) use ($row) {
                            return $row->$col ?? null;
                        });
                    })
                    ->filter()
                    ->unique()
                    ->toArray();

                $this->clearUnusedFiles($folder, $usedFiles);
            }

            $productFiles = Storage::allFiles('public/product');
            $productFileNames = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $productFiles);

            $usedProductFiles = Items::pluck('portrait_img')
                ->merge(Items::pluck('landscape_img'))
                ->merge(Items::pluck('url'))
                ->merge(ItemsImages::pluck('image'))
                ->merge(ItemsAttributes::where('field_type', 6)->pluck('option'))
                ->filter()
                ->unique()
                ->toArray();

            foreach ($productFileNames as $file) {
                if (!in_array($file, $usedProductFiles)) {
                    $this->common->deleteImageToFolder('product', $file);
                }
            }

            $settingFiles = Storage::allFiles('public/setting');
            $settingFileNames = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $settingFiles);

            $usedSettingFiles = collect([
                Page::pluck('icon'),
                Social_Link::pluck('image'),
                Onboarding_Screen::pluck('image')
            ])->flatten()->filter()->unique()->toArray();

            $settingData = Setting_Data();

            foreach ($settingFileNames as $file) {

                if (!in_array($file, $usedSettingFiles)) {

                    if (
                        $settingData['app_logo'] != $file &&
                        $settingData['panel_login_page_bg_image'] != $file
                    ) {
                        $this->common->deleteImageToFolder('setting', $file);
                    }
                }
            }

            return response()->json(array('status' => 200, 'success' => __('label.data_clear_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function clearAllFilesFromFolder($folder)
    {
        $files = Storage::allFiles("public/$folder");

        foreach ($files as $file) {
            $fileName = pathinfo($file, PATHINFO_BASENAME);
            $this->common->deleteImageToFolder($folder, $fileName);
        }
    }
    public function clearUnusedFiles($folder, $usedFiles)
    {
        $files = Storage::allFiles("public/$folder");
        $fileNames = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $files);

        foreach ($fileNames as $file) {
            if (!in_array($file, $usedFiles)) {
                $this->common->deleteImageToFolder($folder, $file);
            }
        }
    }
    public function DownloadSqlFile()
    {
        try {

            Artisan::call('config:clear');

            $storageAt = storage_path() . "/app/public/database";
            if (!file_exists($storageAt)) {
                File::makeDirectory($storageAt, 0755, true, true);
            }

            $mysqlHostName = env('DB_HOST');
            $mysqlUserName = env('DB_USERNAME');
            $mysqlPassword = env('DB_PASSWORD');
            $DbName = env('DB_DATABASE');

            // get all table name
            $result = DB::select("SHOW TABLES");
            $prep = "Tables_in_$DbName";

            foreach ($result as $res) {
                $tables[] =  $res->$prep;
            }

            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword", array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $statement = $connect->prepare("SHOW TABLES");
            $statement->execute();
            $result = $statement->fetchAll();

            $output = '';
            foreach ($tables as $table) {

                $show_table_query = "SHOW CREATE TABLE " . $table . "";
                $statement = $connect->prepare($show_table_query);
                $statement->execute();
                $show_table_result = $statement->fetchAll();

                foreach ($show_table_result as $show_table_row) {
                    $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
                }
                $select_query = "SELECT * FROM " . $table . "";
                $statement = $connect->prepare($select_query);
                $statement->execute();
                $total_row = $statement->rowCount();

                for ($count = 0; $count < $total_row; $count++) {
                    $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
                    $table_column_array = array_keys($single_result);
                    $table_value_array = array_values($single_result);
                    $output .= "\nINSERT INTO $table (";
                    $output .= "`" . implode("`, `", $table_column_array) . "`) VALUES (";
                    $output .= "'" . implode("', '", $table_value_array) . "');\n";
                }
            }

            $file_name = App_Name() . '_db_' . date('d_m_Y') . '.sql';
            $file_handle = fopen(storage_path() . '/app/public/database/' . $file_name, 'w+');
            fwrite($file_handle, $output);
            fclose($file_handle);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize(storage_path() . '/app/public/database/' . $file_name));
            ob_clean();
            flush();
            readfile(storage_path() . '/app/public/database/' . $file_name);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function CleanDatabase()
    {
        try {

            AddToCart::query()->truncate();
            Article::query()->truncate();
            Attributes::query()->truncate();
            AttributesOption::query()->truncate();
            Banner::query()->truncate();
            Brand::query()->truncate();
            Category::query()->truncate();
            City::query()->truncate();
            Customer::query()->truncate();
            CustomerAddress::query()->truncate();
            TrackIntrest::query()->truncate();
            DeliveryAssignment::query()->truncate();
            DeliveryBoy::query()->truncate();
            DeliveryInstructor::query()->truncate();
            Hashtag::query()->truncate();
            Items::query()->truncate();
            ItemsAttributes::query()->truncate();
            ItemsFaq::query()->truncate();
            ItemsImages::query()->truncate();
            Notification::query()->truncate();
            NotificationTracking::query()->truncate();
            Offers::query()->truncate();
            Onboarding_Screen::query()->truncate();
            Orders::query()->truncate();
            OrderCancel::query()->truncate();
            OrdersItems::query()->truncate();
            OrdersTracking::query()->truncate();
            Page::query()->truncate();
            Promocode::query()->truncate();
            Reason::query()->truncate();
            ReturnRequest::query()->truncate();
            Review::query()->truncate();
            Section::query()->truncate();
            SellerRequest::query()->truncate();
            Settelment::query()->truncate();
            Social_Link::query()->truncate();
            StockStatus::query()->truncate();
            Taxes::query()->truncate();
            Transaction::query()->truncate();
            Type::query()->truncate();
            Vehicle::query()->truncate();
            Wishlist::query()->truncate();
            ZipCode::query()->truncate();
            Zones::query()->truncate();
            ZonesValue::query()->truncate();

            return response()->json(array('status' => 200, 'success' => __('label.data_clean_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
