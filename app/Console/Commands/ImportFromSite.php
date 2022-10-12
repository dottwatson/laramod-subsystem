<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use App\Crud\Fields\RelationHandlers\SubscriptionDigitalContentRelationHandler;
use App\Helpers\Import\AttributesFormatter;
use App\Models\Import\Prodotto;
use Illuminate\Support\Facades\DB as LaravelDB;

use App\Helpers\Import\FeaturesFormatter;
use App\Helpers\Import\TagsFormatter;
use App\Helpers\Import\CombinationsFormatter;
use App\Helpers\Import\CustomerFormatter;
use App\Helpers\Import\AddressFormatter;
use App\Models\Attachments\DigitalContentAttachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Order;
use Cart;
use Module;
use COnfiguration;


use App\Models\Customer;
use Customer as PSCustomer;
use App\Models\DigitalContent;
use App\Models\Subscription;
use Product;
use Language;
use Attachment as PrestashopAttachment;
use Dottwatson\CrudGenerator\Models\Attachment;
use Maatwebsite\Excel\Concerns\ToArray;
use SplFileInfo;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;

use SpecificPrice;
use stdClass;

class ImportFromSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:site {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import from site';

    protected $importPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->importPath = realpath(app_path('../../../../gestione/import'));
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        switch($this->argument('action')){
            case 'prepare-products':
                $this->prepareProducts();
            break;
            case 'finalize-products':
                $this->finalizeProducts();
            break;
            case 'finalize-app':
                $this->finalizeAppProducts();
            break;

            case 'prepare-customers':
                $this->prepareCustomers();
            break;

            case 'prepare-addresses':
                $this->prepareAddresses();
            break;

            case 'prepare-orders':
                $this->prepareOrders();
            break;
            case 'import-orders':
                $this->importOrders();
            break;
            case 'test':
                $this->test();
            break;
        }

    }

    




    /**
     * prepara i prodotti
     *
     * @return void
     */
    public function prepareProducts()
    {
        $start = now();


        $this->info(date('Y-m-d H:i:s').' - Preparing...');
        $totalProducts = 0;

        LaravelDB::connection('import')->table('import_products')->truncate();
        LaravelDB::connection('import')->table('import_combinations')->truncate();
        LaravelDB::connection('import')->table('import_attachments')->truncate();
        LaravelDB::connection('import')->table('import_digital_content')->truncate();
        LaravelDB::connection('import')->table('import_isbn')->truncate();
        LaravelDB::connection('import')->table('import_digital_content')->truncate();
        LaravelDB::connection('import')->table('import_related_products')->truncate();
        LaravelDB::connection('import')->table('import_special_prices')->truncate();
        LaravelDB::connection('import')->close();

        foreach(Prodotto::where(LaravelDB::raw(1),'=',1)->cursor() as $prodotto){
            $data = $prodotto->toPSArray();

            foreach($data['immagini'] as &$immagine){
                $immagine = "https://www.cgems.it/Repository/".$immagine;
            }

            if($data['allegati']){
                foreach($data['allegati'] as $k=>$allegato){
                    $data['allegati'][$k]['file'] = "https://www.cgems.it/RepositoryPDF/".$allegato['file'];

                    LaravelDB::connection('import')->table('import_attachments')->insert([
                        'id_product'    => $data['id'],
                        'title'         => $this->sanitizeText($data['allegati'][$k]['titolo'],29),
                        'file'          => $data['allegati'][$k]['file']
                    ]);
                }
            }

            if($data['file_digitale']){
                $data['file_digitale'] = "https://www.cgems.it/RepositoryPDF2/".$data['file_digitale'];

                LaravelDB::connection('import')->table('import_digital_content')->insert([
                    'id_product'    => $data['id'],
                    'title'         => $this->sanitizeText($data['nome'],255),
                    'file'          => $data['file_digitale']
                ]);
            }

            if($data['collegati']){
                foreach($data['collegati'] as $relatedId){
                    LaravelDB::connection('import')->table('import_related_products')->insert([
                        'id_product'            => $data['id'],
                        'id_related_product'    => $relatedId
                    ]);
                }
            }

            LaravelDB::connection('import')->table('import_products')->insert(
                [
                    'id'                => $data['id'],
                    'code'              => $data['codice'],
                    'name'              => $this->sanitizeText($data['nome'],120),
                    'categories'        => implode(',',$data['categorie']),
                    'description'       => $this->sanitizeText($data['descrizione'],3000,['p'],true),
                    'description_short' => $this->sanitizeText($data['descrizione_breve'],400,[],true),
                    'active'            => $data['attivo'],
                    'price'             => $data['prezzo'],
                    'tags'              => TagsFormatter::format($data['tags']),
                    'features'          => FeaturesFormatter::format($data['caratteristiche']),
                    'images'            => implode(',',$data['immagini'])
                ]
            );
            
            $combinazioniData = CombinationsFormatter::format($data['combinazioni']);
            
            if(!$data['combinazioni']){
                LaravelDB::connection('import')->table('import_isbn')->insert([
                    'type'=>'product',
                    'reference'=>$data['codice'],
                    'code' => $data['isbn']
                ]);
            }
            else{
                foreach($data['combinazioni'] as $comboType=>$combo){
                    LaravelDB::connection('import')->table('import_isbn')->insert([
                        'type'=>'combination',
                        'reference'=>$combo['codice'],
                        'code' => $combo['isbn']
                    ]);
    
                    LaravelDB::connection('import')->table('import_combinations')->insert(
                        [
                            'reference'     => $data['codice'],
                            'code'          => $combo['codice'],
                            'isDefault'     => (int)($comboType == 'cartaceo'),
                            'isbn'          => $combo['isbn'],
                            'impact_price'  => $combo['impatto_sul_prezzo'],
                            'impact_weight' => $combo['impatto_sul_peso'],
                            'attributes'    => $combinazioniData[$comboType]['attributes'],
                            'values'        => $combinazioniData[$comboType]['values'],
                            'quantity'      => $combo['quantita'],
                        ]
                    );
                }
    
            }

            if($data['prezzi_speciali']){
                foreach($data['prezzi_speciali'] as $specialPrice){
                    LaravelDB::connection('import')->table('import_special_prices')->insert([
                        'price'            => $specialPrice['prezzo'],
                        'combo_reference'  => $specialPrice['riferimento_combinazione']
                    ]);
                }
            }

            $this->info(date('Y-m-d H:i:s').' - Created data for product id '.$data['id']);

            $totalProducts++;
        }

        $stop= now();

        $this->info(date('Y-m-d H:i:s').' - Processed  '.$totalProducts);
        $this->info(date('Y-m-d H:i:s').' - Data generated in '.$stop->diff($start)->format('%H:%I:%S'));


        $this->info(date('Y-m-d H:i:s').' - Saving data...');



        //save in memory tables
        LaravelDB::connection('import')->close();

        $this->info(date('Y-m-d H:i:s').' - Data created in '.storage_path('/import/generated'));

        $productsFile       = config("database.connections.import.tables.import_products.source");
        $newFile            = $this->importPath.'/'.date('YmdHis').'-import-products.csv';
        copy($productsFile,$newFile);
        $this->info(date('Y-m-d H:i:s').' - Copied products in '.$newFile);

        $combinationsFile   = config("database.connections.import.tables.import_combinations.source");
        $newFile            = $this->importPath.'/'.date('YmdHis').'-import-combinations.csv';
        copy($combinationsFile, $newFile);
        $this->info(date('Y-m-d H:i:s').' - Copied combinations in '.$newFile);
    }



    /**
     * dopo l'import creai gli attachment, e i files allegati al prodotto digitale
     *
     * @return void
     */
    public function finalizeProducts()
    {
        // update isbn on products and combinations
        // DONE: uncomment to redo again
        $this->info(date('Y-m-d H:i:s').' - Updating ISBN ...');
        foreach(LaravelDB::connection('import')->table('import_isbn')->cursor() as $row){
            if($row->type == 'product'){
                $this->info(date('Y-m-d H:i:s')." - Updating ISBN {$row->code} for product reference $row->reference");
                LaravelDB::table('ps_product')->where('reference',$row->reference)->update(['isbn'=>$row->code]);
            }
            else{
                $this->info(date('Y-m-d H:i:s')." - Updating ISBN {$row->code} for combination reference $row->reference");
                LaravelDB::table('ps_product_attribute')->where('reference',$row->reference)->update(['isbn'=>$row->code]);
            }
        }
        
        //create attachments
        //DONE: uncomment to redo again
        $this->info(date('Y-m-d H:i:s').' - Creating attachments for product ...');

        foreach(LaravelDB::connection('import')->table('import_attachments')->cursor() as $row){
            $this->info(date('Y-m-d H:i:s')." - For product #{$row->id_product} creating attachment from file {$row->file}");
            $this->makeProductAttachment($row->id_product,$row->file,$row->title);
        }


        // create related products
        // DONE: uncomment to redo again
        LaravelDB::table('ps_accessory')->truncate();
        foreach(LaravelDB::connection('import')->table('import_related_products')->cursor() as $row){
            // "id_product": 330,
            // "id_related_product": 335
    
            $this->info(date('Y-m-d H:i:s')." - For product #{$row->id_product} creating relation #{$row->id_related_product}");
            // LaravelDB::table('ps_accessory')->where('id_product_1',$row->id_product)->delete();
            LaravelDB::table('ps_accessory')->insert(['id_product_1'=>$row->id_product,'id_product_2'=>$row->id_related_product]);
        }
        
        //set specific prices
        //DONE: uncomment to redo again
        LaravelDB::table('ps_specific_price')->truncate();
        foreach(LaravelDB::connection('import')->table('import_special_prices')->cursor() as $specialPrice){
            
            $comboInfo      = LaravelDB::table('ps_product_attribute')->where('reference',$specialPrice->combo_reference)->first();
            $productInfo    = LaravelDB::table('ps_product_shop')->where('id_product',$comboInfo->id_product)->first();

            $reduction      = ($specialPrice->price - $productInfo->price);
            // $reduction      = ($productInfo->price - $specialPrice->price);

            $this->info(date('Y-m-d H:i:s')." - For product #{$comboInfo->id_product} creating special price for combination #{$comboInfo->id_product_attribute}");

            $price = new SpecificPrice;

            $price->id_specific_price_rule  = 0;
            $price->id_cart                 = 0;
            $price->id_product              = $comboInfo->id_product;
            $price->id_shop                 = 1;
            $price->id_shop_group           = 0;
            $price->id_currency             = 0;
            $price->id_country              = 0;
            $price->id_group                = 0;
            $price->id_customer             = 0;
            $price->id_product_attribute    = $comboInfo->id_product_attribute;
            $price->price                   = -1;
            $price->from_quantity           = 1;
            $price->reduction               = $reduction;
            $price->reduction_tax           = 1;
            $price->reduction_type          = 'amount';
            $price->from                    = '0000-00-00 00:00:00';
            $price->to                      = '0000-00-00 00:00:00';

            $price->save();
        }



    }


    /**
     * crea le relazioni in app e gli attachment digitali
     *
     * @return void
     */
    public function finalizeAppProducts()
    {
        //create digital files to be readed from users
        //DONE: uncomment to redo again
        $this->info(date('Y-m-d H:i:s').' - Removing older crud attachments...');
        DigitalContentAttachment::all()->each(function($item){
            $item->delete();
        });

        foreach(LaravelDb::connection('import')->table('import_digital_content')->cursor() as $digitalContentFile){
            $this->info(date('Y-m-d H:i:s')." - Checking {$digitalContentFile->file} for product #{$digitalContentFile->id_product}");

            $basename           = basename($digitalContentFile->file);
            $path               = preg_replace('#'.preg_quote($basename).'$#','',$digitalContentFile->file);
            $downloadFileName   = $path.rawurlencode($basename);
            
            $entity = DigitalContent::find($digitalContentFile->id_product);
            if($entity){
                $this->info(date('Y-m-d H:i:s')." - Creating {$downloadFileName} for product #{$digitalContentFile->id_product}");

                try{
                    $entity->makeAttachment('allegati',$downloadFileName,$digitalContentFile->title,["downloadable"=>0,"available_for_consultation"=>1]);
                }
                catch(\Exception $e){
                    $this->logError('create digital content',$e->getMessage());
                    $this->error(date('Y-m-d H:i:s')." - product #{$digitalContentFile->id_product} error: {$e->getMessage()}");
                }
            }
            else{
                $this->logError('create digital content',"Product #{$digitalContentFile->id_product} is not a valid digital content");
                $this->error(date('Y-m-d H:i:s')." - Product #{$digitalContentFile->id_product} is not a valid digital content");
            }
        }

        //create digital files to be readed from users
        //DONE: uncomment to redo again
        LaravelDb::table('app_subscriptions_digital_contents')->truncate();
        foreach(LaravelDB::connection('import')->table('abbonamenti')->cursor() as $abbonamentoRow){
            // dump($abbonamentoRow);

            $abbonamento = Subscription::find($abbonamentoRow->prc_prodotto);

            if(!$abbonamento){
                $this->logError('subscription association',"Subscription #{$abbonamentoRow->prc_prodotto} is not a valid subscription");
                $this->error(date('Y-m-d H:i:s')." - Subscription #{$abbonamentoRow->prc_prodotto} is not a valid subscription");
            }
            else{
                $digitalContent = DigitalContent::find($abbonamentoRow->prc_collegato);
                if(!$digitalContent){
                    $this->logError('subscription association',"Digital content #{$abbonamentoRow->prc_collegato} is not a valid item for subscription #{$abbonamentoRow->prc_prodotto}");
                    $this->error(date('Y-m-d H:i:s')." - Digital content #{$abbonamentoRow->prc_collegato} is not a valid item for subscription #{$abbonamentoRow->prc_prodotto}");
                }
                else{
                    // $abbonamento->deleteRelations('digital_contents');
                    $this->info(date('Y-m-d H:i:s')." - Linking Digital content #{$abbonamentoRow->prc_collegato} with subscription #{$abbonamentoRow->prc_prodotto}");
                    $abbonamento->makeRelation('digital_contents',$abbonamentoRow->prc_collegato);
                }
    
            }
        }
    }






    /**
     * prpare i dati da importare dei clienti
     *
     * @return void
     */
    public function prepareCustomers()
    {
        
        $this->info(date('Y-m-d H:i:s').' - Preparing data ...');
        LaravelDB::table('import_customers')->truncate();
        LaravelDB::table('import_customers_duplicated')->truncate();
        
        $duplicatedEmailsIds    = []; 
        $duplicatedEmailsRows   = LaravelDB::connection('import')->select("
        SELECT Cl_Email, COUNT(Cl_Email) AS counted 
        FROM clienti  
            GROUP BY Cl_Email 
            HAVING counted > 1 
            ORDER BY counted DESC"
        );
        
        foreach($duplicatedEmailsRows as $duplicatedRow){
            $rows       = LaravelDB::connection('import')->table('clienti')->where('Cl_Email',$duplicatedRow->cl_email)->get();
            $cnt        = 0;
            $baseRow    = new stdClass;
            $rows->each(function($row) use (&$cnt,&$baseRow,&$duplicatedEmails){
                if($cnt == 0){
                    $baseRow = $row;
                }
                else{
                    $duplicatedEmailsIds[] = $row->cl_id;
                    LaravelDB::table('import_customers_duplicated')->insert([
                        'id'                => $baseRow->cl_id,
                        'email'             => $baseRow->cl_email,
                        'idweb'             => $baseRow->idweb,
                        'code'              => $baseRow->cl_cod,
                        'duplicated_id'     => $row->cl_id,
                        'duplicated_email'  => $row->cl_email,
                        'duplicated_idweb'  => $row->idweb,
                        'duplicated_code'   => $row->cl_cod,
                    ]);
                }
                $cnt++;
            });
        }

        $query = LaravelDB::connection('import')->table('clienti');
        if($duplicatedEmailsIds){
            $query->whereNotIn('Cl_Id',$duplicatedEmailsIds);
        }

        // $query->limit(500);
        foreach($query->cursor() as $customer){
            $customerData  = CustomerFormatter::format($customer);
            $this->info(date('Y-m-d H:i:s')." - Found customer #{$customerData['id']} idweb {$customerData['idweb']} code {$customerData['code']}");
            LaravelDB::table('import_customers')->insert($customerData);
        }
        
        
        $this->exportDBTableInto('import_customers_duplicated',storage_path('/import/generated/import_customers_duplicated.csv'));
        $this->exportDBTableInto('import_customers',$this->importPath.'/'.date('YmdHis').'-import-customers.csv');
    }


    /**
     * prepar gli indirizzi dei clienti
     *
     * @return void
     */
    public function prepareAddresses()
    {
        LaravelDB::table('import_addresses')->truncate();

        foreach(LaravelDb::table('ps_customer')->cursor() as $customer){
            $olderCustomer = LaravelDB::connection('import')->table('clienti')->where('Cl_Id',$customer->id_customer)->first();
        
            $addresses = AddressFormatter::format(['original'=>$olderCustomer,'customer'=>$customer]);

            foreach($addresses as $address){
                $this->info(date('Y-m-d H:i:s')." - Found Address {$address['alias']} for customer #{$address['customer_id']}");
                LaravelDB::table('import_addresses')->insert($address);
            }
        }

        $this->exportDBTableInto('import_addresses',$this->importPath.'/'.date('YmdHis').'-import-addresses.csv');
    }



    public function prepareOrders()
    {
        foreach(LaravelDB::connection('import')->table('ordini')->cursor() as $order){
            $customer = new PSCustomer($order->ord_cliente);
            $addresses = $customer->getAddresses(1);

            $invoiceAddress = null;
            $deliveryAddress = null;
            foreach($addresses as $address){
                if($address['alias'] == 'Il mio Indirizzo'){
                    $invoiceAddress = $address;
                }
                if($address['alias'] == 'Spedizione'){
                    $deliveryAddress = $address;
                }
            }

            if(!$deliveryAddress){
                $deliveryAddress = $invoiceAddress;
            }
            
            
            // Cart information
            $new_cart                       = new Cart();
            $new_cart->id_customer          = $order->ord_cliente;
            $new_cart->id_address_delivery  = $deliveryAddress['id_address'];
            $new_cart->id_address_invoice   = $invoiceAddress['id_address'];
            $new_cart->id_lang = 1;
            $new_cart->id_currency = 1;
            $new_cart->id_carrier = 5;

            $new_cart->add();

            // $new_cart->updateQty( 1, 1181,1500);

            // Add the products to the cart
            config(['prestashop.routeName' => 'route']);

            $orderCreationUrl = ps_route('front::import.create_order',[
                'cartId'=>$new_cart->id,
                'products'=>[
                    [1, 1181,1500]
                ]
            ]);
            dd($orderCreationUrl);
            // Creating order from cart
            $payment_module = Module::getInstanceByName('importpayment');
            
            $result = $payment_module->validateOrder($new_cart->id, 21, $new_cart->getOrderTotal(), 'Unknown', 'Test');

            // Get the order id after creating it from the cart.
            $id_order = Order::getOrderByCartId($new_cart->id);
            $new_order = new Order($id_order);

            exit;
        }




    }

    public function importOrders()
    {
    }


    public function test()
    {
    }














    /**
     * crea gli allegati per il file
     *
     * @param integer $productId
     * @param string $file
     * @param string|null $name
     * @return bool
     */
    function makeProductAttachment(int $productId,string $file,string $name = null)
    {
        $basename           = basename($file);
        $path               = preg_replace('#'.preg_quote($basename).'$#','',$file);
        $downloadFileName   = $path.rawurlencode($basename);
        $file               = $downloadFileName;

        $this->info(date('Y-m-d H:i:s')." - ----> Retrieving {$file}");

        try{
            
            $tmpDisk        = Storage::disk('tmp');
        
            $names          = [];
            $descriptions   = [];
    
            $fileName                   = basename($file);
            $blocks                     = explode('.',$fileName,2);
            $extension                  = array_pop($blocks);
            $fileNameWithoutExtension   = implode('.',$blocks);
    
            //create a tmp copy of file
            $tmpName = Str::uuid().'.'.$extension;
    
            $downloadContext = stream_context_create([
                "ssl"=>array(
                    "verify_peer"       => false,
                    "verify_peer_name"  => false,
                ),
            ]);  
            

            $tmpDisk->put($tmpName,file_get_contents($file,false, $downloadContext));
            $newFilePath = $tmpDisk->path($tmpName);
    
            $file       = new SplFileInfo($newFilePath);
            $mimeType   = (new FileinfoMimeTypeGuesser())->guessMimeType($newFilePath);
            if(!$mimeType){
                $mimeType = 'application/octet-stream';
            }
    
            if($name === null){
                $name = str_replace(["-","_"],' ',$fileNameWithoutExtension);
            }
    
            foreach(Language::getLanguages() as $language){
                $langId = $language['id_lang'];
                $names[$langId]           = $name;
                $descriptions[$langId]    = '';
            }
    
            do {
                $uniqid = sha1(microtime());
            } 
            while (file_exists(_PS_DOWNLOAD_DIR_ . $uniqid));
    
            file_put_contents(_PS_DOWNLOAD_DIR_ . $uniqid, $tmpDisk->get($tmpName) );
            
            $attachment                 = new PrestashopAttachment();
            $attachment->name           = $names;
            $attachment->description    = $descriptions;
            
            $attachment->file           = $uniqid;
            $attachment->mime           = $mimeType;
            $attachment->file_name      = $fileName;
    
            $res = $attachment->add();
            if (!$res) {
                return false;
            } 
    
    
            return $attachment->attachProduct($productId);
        }
        catch(\Exception $e){
            $errorMessage = $e->getMessage();

            $this->error(date('Y-m-d H:i:s')." - ----> {$errorMessage}");

            $this->error("Impossibile utilizzare il file");

            $this->logError("create product attachment","Impossibile Importare {$file}: {$errorMessage}");
        }
    }

    /**
     * logs occurred errors
     *
     * @param string $action
     * @param string $message
     * @return void
     */
    protected function logError(string $action,string $message)
    {
        $date = now()->format('Y-m-d H:i:s');
        LaravelDB::connection('import')->table('import_errors')->insert([
                'date'          => $date,
                'operation'     => $action,
                'message'       => $message
            ]);            
    }

    /**
     * adapt text format and length
     *
     * @param string $text
     * @param integer $length
     * @param array $allowedTags
     * @param boolean $nl2br
     * @return string
     */
    public function sanitizeText(string $text,int $length = 3000,array $allowedTags = [],bool $nl2br = false)
    {
        $text = strip_tags($text,$allowedTags);
        $text = str_replace(["\r\n","\n\r","\r","\n"],'',$text);

        $text = preg_replace('#\s+#',' ',$text);

        if($nl2br){
            $text = nl2br($text);
        }

        if(strlen($text) > $length){
            $text = substr($text,0,$length).'...';
        }

        return trim($text);
    }



    protected function exportDBTableInto($table,$file)
    {
        $columns = [];
        $rows    = [];
        $columnsQuery = LaravelDB::select("SHOW COLUMNS FROM {$table}");
    
        foreach($columnsQuery as $columnInfo){
            $columns[] = $columnInfo->Field;
        }

        $rows[] = implode('|',$columns);

        foreach(LaravelDB::table($table)->cursor() as $row){
            $rowData = [];
            foreach($columns as $column){
                $rowData[$column] = $row->{$column};
            }

            $rows[] = implode('|',$rowData);
        }


        $rows = implode("\n",$rows);

        return file_put_contents($file,$rows,LOCK_EX);
    }
}
