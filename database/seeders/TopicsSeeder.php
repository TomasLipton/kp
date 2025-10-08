<?php

namespace Database\Seeders;

use App\Models\Topics;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TopicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        File::ensureDirectoryExists(storage_path('app/public/topics'));
        $topics = [
            [
                'slug' => 'single-text-questions',
                'name_pl' => 'Pytania tekstowe',
                'name_ru' => 'Текстовые вопросы',
                'name_by' => 'Тэкставыя пытанні',
                'name_uk' => 'Текстові питання',
                'description_pl' => 'Pytania z wyborem odpowiedzi tekstowych',
                'description_ru' => 'Вопросы с выбором текстовых ответов',
                'description_by' => 'Пытанні з выбарам тэкставых адказаў',
                'description_uk' => 'Питання з вибором текстових відповідей',
                'picture' => 'topics/single-text-questions.jpg',
            ],
            [
                'slug' => 'year-questions',
                'name_pl' => 'Pytania o rok',
                'name_ru' => 'Вопросы о годе',
                'name_by' => 'Пытанні пра год',
                'name_uk' => 'Питання про рік',
                'description_pl' => 'Pytania wymagające podania roku',
                'description_ru' => 'Вопросы, требующие указания года',
                'description_by' => 'Пытанні, якія патрабуюць указання года',
                'description_uk' => 'Питання, що вимагають вказівки року',
                'picture' => 'topics/year-questions.jpg',
            ],
            [
                'slug' => 'number-questions',
                'name_pl' => 'Pytania numeryczne',
                'name_ru' => 'Числовые вопросы',
                'name_by' => 'Лікавыя пытанні',
                'name_uk' => 'Числові питання',
                'description_pl' => 'Pytania wymagające podania liczby',
                'description_ru' => 'Вопросы, требующие указания числа',
                'description_by' => 'Пытанні, якія патрабуюць указання ліку',
                'description_uk' => 'Питання, що вимагають вказівки числа',
                'picture' => 'topics/number-questions.jpg',
            ],
            [
                'slug' => 'date-month-questions',
                'name_pl' => 'Pytania o dzień i miesiąc',
                'name_ru' => 'Вопросы о дне и месяце',
                'name_by' => 'Пытанні пра дзень і месяц',
                'name_uk' => 'Питання про день і місяць',
                'description_pl' => 'Pytania wymagające podania dnia i miesiąca',
                'description_ru' => 'Вопросы, требующие указания дня и месяца',
                'description_by' => 'Пытанні, якія патрабуюць указання дня і месяца',
                'description_uk' => 'Питання, що вимагають вказівки дня і місяці',
                'picture' => 'topics/date-month-questions.jpg',
            ],
            [
                'slug' => 'date-month-year-questions',
                'name_pl' => 'Pytania o pełną datę',
                'name_ru' => 'Вопросы о полной дате',
                'name_by' => 'Пытанні пра поўную дату',
                'name_uk' => 'Питання про повну дату',
                'description_pl' => 'Pytania wymagające podania pełnej daty',
                'description_ru' => 'Вопросы, требующие указания полной даты',
                'description_by' => 'Пытанні, якія патрабуюць указання поўнай даты',
                'description_uk' => 'Питання, що вимагають вказівки повної дати',
                'picture' => 'topics/date-month-year-questions.jpg',
            ],
        ];

        foreach ($topics as $topic) {
            $imageUrl = 'https://picsum.photos/seed/'.$topic['slug'].'/800/600';
            $imageContent = Http::get($imageUrl)->body();
            $imagePath = 'topics/'.$topic['slug'].'.jpg';
            Storage::disk('public')->put($imagePath, $imageContent);

            Topics::create($topic);
        }
    }
}
