# laravel-basic
Instructions

  git clone https://github.com/john-Lin-mac/Laravel-basic.git 

  cd Laravel-basic

  php composer update

  mv .env.example  .env

  vi .env

   DB_DATABASE=my_datebase
   ( Create a new database with the language family utf8mb4_unicode_ci )
   
   DB_PASSWORD=my_password

  php artisan key:generate

  sudo chown -R daemon:daemon storage
  
  sudo chmod -R 775 storage

  sudo chown -R daemon:daemon public
  
  sudo chmod -R 775 public

  php artisan migrate

php laravel commonly used classes: in app\Models\basic, they are: select SelectModel, upload UpModel, link UrlModel, file FileModel, image ImageModel in app\Http\Controller\BasicController, there are code for usage


select SelectModel

    $select_builder = new SelectModel();

    $builder = DB::table('select')->select('id','name')->orderBy('id')->get()->toArray(); //Enter Query Builder

    $select_builder->select_search_all_builder($builder); 

    $select_builder->select_data_change('name','id'); //Set display field and value

    $select_builder->select_menu('select_builder',array('name' => 'choose name','value' => '2')); //output

upload UpModel

    $up = new UpModel(); 

    $up->set_upload('images','file'); //Set path and file name

    $info = $up->up_load(); //perform upload

link UrlModel

    $data['url_str'] = UrlModel::url_str(['name' => 'name','type' => 'type']); //generate string link

    $data['url_array'] = UrlModel::url_array(['name' => 'name','type' => 'type'],'?'); //Generate array link, start default? Can be customized to &


file FileModel

    $delete_builder = DB::table('file')->select('file_url')->where('id',$id)->first(); //Enter Query Builder

    $file->set_DataBuilder('images',$delete_builder,'file_url'); //set path and delete field

    $successFile = $file->file_delete(); //execute delete file


image ImageModel

    $img = new ImageModel();

    $img->set_path('public/images/');

    if($request->has('width')){

        $width = $request->input('width');
    
        $height = $request->input('height');
    
    }else{

        return false;
    
    }

    return $img->show($fileName,$width,$height); //Display image, custom width, auto-thumbnail



