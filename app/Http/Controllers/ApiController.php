<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Search;
use App\Models\Question;

class ApiController extends Controller
{
    public function form()
    {
        $formData = session('form_data', [
            'tagged' => '',
            'fromdate' => '',
            'todate' => ''
        ]);
        if(is_null(session('questions'))) {
            session()->put('questions', []);
        }
        /*$formData['tagged'] = '';
        $formData['fromdate'] = '';
        $formData['todate'] = '';*/
        $questions = session('questions', []);
        $searches = Search::with('questions')->get();
        return view('list', [
            'formData' => $formData,
            'questions' => $questions,
            'searches' => $searches,
        ]);
    }

    public function handleRequest(Request $request)
    {
        $action = $request->input('action');
        if ($action == 'search') {
            return $this->search($request);
        } elseif ($action == 'save') {
            return $this->save($request);
        } elseif ($action == 'clean') {
            return $this->clean($request);
        } else {
            return redirect()->back()->with('error', 'Acción no válida.');
        }
    }

    public function search(Request $request) {
        $tagged = $request->input('tagged');
        session()->put('form_data', $request->only(['tagged', 'fromdate', 'todate']));
        if(!is_null($tagged) && $tagged !== '') {
            $fromdate = $request->input('fromdate') ? strtotime($request->input('fromdate')) : null;
            $todate = $request->input('todate') ? strtotime($request->input('todate')) : null;
            $questions = $this->questions($tagged, $fromdate, $todate);
            session()->put('questions', $questions);
            return redirect('/');
        } else {
            session()->flash('error', 'Error al mostrar los datos.');
            session()->put('questions', []);
            return redirect('/');
        }     
    }

    private function save(Request $request)
    {
        $tagged = $request->input('tagged');
        session()->put('form_data', $request->only(['tagged', 'fromdate', 'todate']));
        if(!is_null($tagged) && $tagged !== '') {
            $fromdate = $request->input('fromdate') ? strtotime($request->input('fromdate')) : null;
            $todate = $request->input('todate') ? strtotime($request->input('todate')) : null;
            $questions = $this->questions($tagged, $fromdate, $todate);
            $search = Search::updateOrCreate(
                ['tag' => $tagged, 'fromdate' => date('Y-m-d H:i:s', $fromdate), 'todate' => date('Y-m-d H:i:s', $todate)],
            );  
            foreach ($questions as $question) {
                Question::updateOrCreate(
                    ['question_id' => $question['question_id']],
                    [
                        'search_id' => $search->id,
                        'title' => $question['title'],
                        'link' => $question['link'],
                        'creation_date' => date('Y-m-d H:i:s', $question['creation_date']),
                    ]
                );
            }
            session()->flash('success', 'Resultados guardados correctamente.');
            session()->put('questions', $questions);
            return redirect('/');
        }
        session()->flash('success', 'Error al guardar los datos.');
        session()->put('questions', []);
        return redirect('/');
    }

    private function questions($tagged, $fromdate, $todate)
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.stackexchange.com/2.3/questions', [
            'query' => [
                'site' => 'stackoverflow',
                'order' => 'desc',
                'sort' => 'activity',
                'tagged' => $tagged,
                'fromdate' => $fromdate,
                'todate' => $todate,
            ]
        ]);
        $questions = json_decode($response->getBody(), true);
        return $questions['items'];
    }

    public function clean(Request $request) {
        session()->put('form_data', [
            'tagged' => '',
            'fromdate' => '',
            'todate' => ''
        ]);
        session()->put('questions', []);
        return redirect('/');
    }

    public function getQuestionsBySearch($searchId)
    {
        $search = Search::with('questions')->find($searchId);

        if (!$search) {
            return response()->json(['error' => 'Búsqueda no encontrada'], 404);
        }

        return response()->json($search->questions);
    }
}
