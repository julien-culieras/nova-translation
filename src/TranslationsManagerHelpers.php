<?php


namespace Sideapps\NovaTranslation;


use Illuminate\Support\Collection;
use JoeDixon\Translation\Drivers\Translation;
use JoeDixon\Translation\Language;

class TranslationsManagerHelpers {

    private Translation $translation;

    public function __construct(Translation $translation) {
        $this->translation = $translation;
    }

    public function getCleanedGroups(Collection $groups):Collection {
        if ($groups->isNotEmpty()) {
            return $groups->diff(collect(config('nova-translation.excluded_groups')));
        }
        return $groups;
    }

    public function getCleanedTranslations(Collection $collection):Collection {
        if ($collection->isNotEmpty()) {
            $transformedCollection = $collection->get('group')->filter(function ($translations, string $key) {
                return in_array($key, config('nova-translation.excluded_groups')) ? false : true;
            });
            return collect([
                'group' => $transformedCollection
            ]);
        }
        return $collection;

    }

    public function findLanguageFromLocale(string $locale):Language {
        return Language::query()
            ->where('language', $locale)
            ->first();
    }

    public function getAllLanguages():Collection {
        return $this->translation->allLanguages()->map(function (string $name, string $locale) {
            return $this->findLanguageFromLocale($locale);
        });
    }
}
