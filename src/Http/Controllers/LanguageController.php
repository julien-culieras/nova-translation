<?php

namespace Sideapps\NovaTranslation\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use JoeDixon\Translation\Drivers\Translation;
use JoeDixon\Translation\Http\Requests\LanguageRequest;
use JoeDixon\Translation\Language;

class LanguageController extends Controller
{
    private $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }

    public function index()
    {
        return response()->json(Language::all());
    }

    public function delete(Language $language) {
        try {
            \JoeDixon\Translation\Translation::query()->where('language_id', $language->id)->delete();
            $language->delete();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function store(LanguageRequest $request)
    {
        $this->translation->addLanguage($request->get('locale'), $request->get('name'));

        return response()->json(['success' => true]);
    }
}
