<?php

namespace Modules\News\Http\Controllers;

use App\Models\CategoryTables;
use App\Models\ChannelTable;
use App\Models\NewsTable;
use App\Models\VersionsTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data = ChannelTable::get();
        $version = VersionsTable::get();
        foreach ($version as $ver){
            if(!preg_match("/[a-z]/i", $ver['version'])){
                $data['version']= $ver['version'];
            }
        }
        return view('news::index', ['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */

    public function populateDB()
    {
        $xmlURL = file_get_contents("https://rss.nytimes.com/services/xml/rss/nyt/World.xml");
        $dataXML = simplexml_load_string($xmlURL);
        $json = json_encode($dataXML);
        $alldata = json_decode($json, true);
        $data = $alldata['channel'];
        ChannelTable::firstOrCreate([
            'title' => $data['title'],
            'link' => $data['link'],
            'copyright' => $data['copyright'],
            'pubDate' => $data['pubDate'],
            'image_url' => $data['image']['url']
        ]);
        foreach ($alldata['channel']['item'] as $item) {
            if ($item['description'] == []) {
                $item['description'] = "";
            }
            $id_news = NewsTable::firstOrCreate([
                'title' => $item['title'],
                'link' => $item['link'],
                'description' => $item['description'],
                'pubDate' => strtotime($item['pubDate'])
            ]);

            if (isset($item['category'])) {
                foreach ($item['category'] as $categ) {
                    CategoryTables::firstOrCreate([
                        'name' => $categ,
                        'news_id' => $id_news->id
                    ]);
                }
            }
        }
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function getData(Request $request)
    {
        $form = $request->all();
        $data = NewsTable::where('title','like','%'.$form['search_value'].'%')
            ->orwhere('description','like','%'.$form['search_value'].'%')
            ->orderBy( $form['type'], $form['sort'])->get();

        $html = '<ul class="ul-style">';
        foreach ($data as $item) {
            $html .= view('components.news',['title' => $item['title'],
                                                  'link' => $item['link'],
                                                  'description' =>$item['description'],
                                                  'date'=>date("D , d M Y h:m:s A",$item['pubDate'])]);
        }
        $html .= '</ul>';
        return response()->json(array('news' => $html));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('news::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('news::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('news::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
