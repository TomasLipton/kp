<?php

namespace Database\Seeders;

use App\Models\Topics;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'slug' => 'traffic-rules',
                'name_pl' => 'Przepisy ruchu drogowego',
                'name_ru' => 'Правила дорожного движения',
                'name_by' => 'Правілы дарожнага руху',
                'name_uk' => 'Правила дорожнього руху',
                'description_pl' => 'Podstawowe przepisy i zasady poruszania się po drogach',
                'description_ru' => 'Основные правила и принципы движения по дорогам',
                'description_by' => 'Асноўныя правілы і прынцыпы руху па дарогах',
                'description_uk' => 'Основні правила та принципи руху по дорогах',
                'picture' => 'topics/traffic-rules.jpg',
            ],
            [
                'slug' => 'road-signs',
                'name_pl' => 'Znaki drogowe',
                'name_ru' => 'Дорожные знаки',
                'name_by' => 'Дарожныя знакі',
                'name_uk' => 'Дорожні знаки',
                'description_pl' => 'Rozpoznawanie i rozumienie znaków drogowych',
                'description_ru' => 'Распознавание и понимание дорожных знаков',
                'description_by' => 'Распазнаванне і разуменне дарожных знакаў',
                'description_uk' => 'Розпізнавання та розуміння дорожніх знаків',
                'picture' => 'topics/road-signs.jpg',
            ],
            [
                'slug' => 'road-markings',
                'name_pl' => 'Oznakowanie drogowe',
                'name_ru' => 'Дорожная разметка',
                'name_by' => 'Дарожная разметка',
                'name_uk' => 'Дорожня розмітка',
                'description_pl' => 'Linie i symbole na jezdni',
                'description_ru' => 'Линии и символы на проезжей части',
                'description_by' => 'Лініі і сімвалы на праезнай частцы',
                'description_uk' => 'Лінії та символи на проїжджій частині',
                'picture' => 'topics/road-markings.jpg',
            ],
            [
                'slug' => 'traffic-lights',
                'name_pl' => 'Sygnalizacja świetlna',
                'name_ru' => 'Светофоры',
                'name_by' => 'Святлафоры',
                'name_uk' => 'Світлофори',
                'description_pl' => 'Zasady korzystania z sygnalizacji świetlnej',
                'description_ru' => 'Правила использования светофоров',
                'description_by' => 'Правілы выкарыстання святлафораў',
                'description_uk' => 'Правила використання світлофорів',
                'picture' => 'topics/traffic-lights.jpg',
            ],
            [
                'slug' => 'parking',
                'name_pl' => 'Parkowanie',
                'name_ru' => 'Парковка',
                'name_by' => 'Паркоўка',
                'name_uk' => 'Паркування',
                'description_pl' => 'Zasady i miejsca parkowania pojazdów',
                'description_ru' => 'Правила и места парковки транспортных средств',
                'description_by' => 'Правілы і месцы паркоўкі транспартных сродкаў',
                'description_uk' => 'Правила та місця паркування транспортних засобів',
                'picture' => 'topics/parking.jpg',
            ],
            [
                'slug' => 'priority-rules',
                'name_pl' => 'Zasady pierwszeństwa',
                'name_ru' => 'Правила приоритета',
                'name_by' => 'Правілы прыярытэту',
                'name_uk' => 'Правила пріоритету',
                'description_pl' => 'Określanie pierwszeństwa przejazdu',
                'description_ru' => 'Определение приоритета проезда',
                'description_by' => 'Вызначэнне прыярытэту праезду',
                'description_uk' => 'Визначення пріоритету проїзду',
                'picture' => 'topics/priority-rules.jpg',
            ],
            [
                'slug' => 'speed-limits',
                'name_pl' => 'Ograniczenia prędkości',
                'name_ru' => 'Ограничения скорости',
                'name_by' => 'Абмежаванні хуткасці',
                'name_uk' => 'Обмеження швидкості',
                'description_pl' => 'Dozwolone prędkości w różnych warunkach',
                'description_ru' => 'Разрешенные скорости в различных условиях',
                'description_by' => 'Дазволеныя хуткасці ў розных умовах',
                'description_uk' => 'Дозволені швидкості за різних умов',
                'picture' => 'topics/speed-limits.jpg',
            ],
            [
                'slug' => 'overtaking',
                'name_pl' => 'Wyprzedzanie',
                'name_ru' => 'Обгон',
                'name_by' => 'Абгон',
                'name_uk' => 'Обгін',
                'description_pl' => 'Bezpieczne manewry wyprzedzania',
                'description_ru' => 'Безопасные маневры обгона',
                'description_by' => 'Бяспечныя маневры абгону',
                'description_uk' => 'Безпечні маневри обгону',
                'picture' => 'topics/overtaking.jpg',
            ],
            [
                'slug' => 'pedestrian-crossings',
                'name_pl' => 'Przejścia dla pieszych',
                'name_ru' => 'Пешеходные переходы',
                'name_by' => 'Пешаходныя пераходы',
                'name_uk' => 'Пішохідні переходи',
                'description_pl' => 'Zasady dotyczące przejść dla pieszych',
                'description_ru' => 'Правила, касающиеся пешеходных переходов',
                'description_by' => 'Правілы, якія тычацца пешаходных пераходаў',
                'description_uk' => 'Правила щодо пішохідних переходів',
                'picture' => 'topics/pedestrian-crossings.jpg',
            ],
            [
                'slug' => 'emergency-vehicles',
                'name_pl' => 'Pojazdy uprzywilejowane',
                'name_ru' => 'Привилегированные транспортные средства',
                'name_by' => 'Прывілеяваныя транспартныя сродкі',
                'name_uk' => 'Привілейовані транспортні засоби',
                'description_pl' => 'Zachowanie wobec pojazdów służb ratunkowych',
                'description_ru' => 'Поведение в отношении транспортных средств экстренных служб',
                'description_by' => 'Паводзіны ў адносінах да транспартных сродкаў экстранных служб',
                'description_uk' => 'Поведінка щодо транспортних засобів екстрених служб',
                'picture' => 'topics/emergency-vehicles.jpg',
            ],
        ];

        foreach ($topics as $topic) {
            $imageUrl = 'https://picsum.photos/seed/' . $topic['slug'] . '/800/600';
            $imageContent = Http::get($imageUrl)->body();
            $imagePath = 'topics/' . $topic['slug'] . '.jpg';
            Storage::disk('public')->put($imagePath, $imageContent);

            Topics::create($topic);
        }
    }
}
