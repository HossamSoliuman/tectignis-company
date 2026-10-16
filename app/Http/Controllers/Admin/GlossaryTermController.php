<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGlossaryTermRequest;
use App\Models\GlossaryTerm;
use Illuminate\Http\RedirectResponse;

/**
 * Fixed translations of key terms, added to the Translations tab prompt.
 */
class GlossaryTermController extends Controller
{
    public function store(StoreGlossaryTermRequest $request): RedirectResponse
    {
        GlossaryTerm::create($request->validated());

        return redirect()->to(route('admin.translations.index').'#glossary')->with('status', 'Glossary term added.');
    }

    public function destroy(GlossaryTerm $glossaryTerm): RedirectResponse
    {
        $glossaryTerm->delete();

        return redirect()->to(route('admin.translations.index').'#glossary')->with('status', 'Glossary term deleted.');
    }
}
