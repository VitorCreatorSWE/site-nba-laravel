<?php

namespace App\Services;

use App\Models\Game;

class GameAnalysis
{
    /**
     * Coluna de games que guarda a analise de cada idioma.
     *
     * PT e o idioma original e sempre foi o unico; EN foi acrescentado depois.
     * Um idioma sem coluna propria cai no PT para nao quebrar a pagina.
     */
    public const COLUMNS = [
        'pt' => 'analysis_text',
        'en' => 'analysis_text_en',
    ];

    public const DEFAULT_LOCALE = 'pt';

    /**
     * Periodo do dia, por idioma, conforme o horario do jogo.
     *
     * Em ingles o par foi escolhido para funcionar com "an {period}" e
     * "in the {period}", que soam naturais tanto com afternoon quanto evening.
     */
    private const PERIODS = [
        'pt' => ['tarde', 'noite'],
        'en' => ['afternoon', 'evening'],
    ];

    private array $templates = [
        // Variação 1 – Noite dramática / Tarde intensa
        [
            'pt' => 'Foi uma {periodo} que ninguém que estava no ginásio vai esquecer tão cedo. O confronto entre o {time_vencedor} e o {time_perdedor} entregou tudo que um grande jogo de basquete precisa ter: intensidade do primeiro ao último segundo, jogadas espetaculares, reviravoltas constantes e uma tensão que só aumentava a cada posse de bola. O placar final de {placar} não conta nem a metade da história do que aconteceu dentro de quadra. Os dois times entraram em campo com mentalidade de final e mostraram isso desde o primeiro período, trocando cestas de três, defesas agressivas e rebotes decisivos. No segundo quarto o jogo já tinha virado um verdadeiro duelo de personalidades, com o placar apertado e o público em pé a cada jogada importante. No intervalo o {time_vencedor} ainda precisava encontrar respostas, mas voltou do vestiário com outra atitude. A defesa apertou, as bolas de três começaram a cair e a equipe encontrou ritmo ofensivo. Mesmo assim o {time_perdedor} não se entregou e respondeu com sequências próprias, mantendo a partida aberta até os minutos finais. Foi no último quarto que a diferença realmente apareceu. O {time_vencedor} mostrou frieza, aproveitou os erros do adversário e fechou o jogo com autoridade. O apito final selou uma vitória merecida emocionante, daquelas que ficam na memória da torcida. Um espetáculo completo de basquete, com tudo o que se espera de um grande confronto.',
        'en' => 'It was an {period} that nobody in that arena will forget anytime soon. The matchup between the {time_vencedor} and the {time_perdedor} delivered everything a great basketball game should have: intensity from the opening tip to the final second, spectacular plays, constant comebacks and a tension that only built with every possession. The final score of {placar} does not tell even half the story of what went on out there. Both teams came out with championship mentality and showed it from the first period, trading three-pointers, swarming defensively and fighting for decisive rebounds. By the second quarter the game had turned into a genuine duel between personalities, with the score tight and the crowd on its feet for every big moment. At halftime the {time_vencedor} still needed answers, but came back out of the locker room with a different attitude. The defense tightened, the threes started falling and the team found its offensive rhythm. Even so the {time_perdedor} refused to quit and answered with runs of its own, keeping the game open until the final minutes. It was in the fourth quarter that the gap really showed. The {time_vencedor} stayed composed, punished the opponent mistakes and closed the game with authority. The final buzzer sealed a well-earned and emotional win, the kind that sticks with a fanbase. A complete basketball show, with everything you expect from a great matchup.',

        ],

        // Variação 2 – Clima de final
        [
            'pt' => 'Quem acompanhou o embate entre {time_vencedor} e {time_perdedor} nesta {periodo} teve a sensação de estar assistindo a um jogo de mata-mata. A partida foi marcada por um ritmo alucinante desde a bola ao alto, com as duas equipes trocando golpes sem dar trégua. O placar de {placar} esconde a verdadeira batalha que aconteceu em cada posse. Defesas travadas, arremessos de três em sequência e rebotes disputados com unhas e dentes transformaram o ginásio em um caldeirão. No segundo quarto o jogo já estava equilibrado e qualquer erro poderia custar caro. Depois do intervalo o {time_vencedor} elevou o nível defensivo e começou a controlar melhor o ritmo. Mesmo sob pressão constante do {time_perdedor}, a equipe manteve a calma e encontrou soluções ofensivas nos momentos certos. O último quarto foi puro drama. Cada cesta era celebrada como se fosse a última, e a torcida não parava de gritar. Quando o apito final soou, o {time_vencedor} confirmou a vitória merecida emocionante.',
        'en' => 'Anyone who watched the battle between the {time_vencedor} and the {time_perdedor} on this {period} felt like they were watching a playoff game. The contest was defined by a relentless pace from the opening tip, with both teams trading blows and showing no mercy. The {placar} score hides the real war that took place on every possession. Locked-down defenses, three-pointers in succession and rebounds fought for tooth and nail turned the arena into a furnace. By the second quarter the game was balanced and any mistake could prove costly. After halftime the {time_vencedor} raised its defensive level and started controlling the pace better. Under constant pressure from the {time_perdedor}, the team kept its composure and found offensive answers at the right moments. The fourth quarter was pure drama. Every basket was celebrated as if it were the last, and the crowd never stopped roaring. When the final buzzer sounded, the {time_vencedor} confirmed a well-earned and emotional win.',

        ],

        // Variação 3 – Narrativa épica
        [
            'pt' => 'Nesta {periodo} especial, o {time_vencedor} e o {time_perdedor} escreveram mais um capítulo emocionante da temporada. O que se viu em quadra foi basquete de alto nível do começo ao fim. O placar final de {placar} é apenas o número que resume uma disputa cheia de reviravoltas, grandes individualidades e muito coração. Desde os primeiros minutos ficou claro que nenhum dos lados estava disposto a ceder. Cestas de três, contra-ataques rápidos e defesas intensas mantiveram o público em estado de alerta constante. No intervalo o jogo ainda estava completamente aberto, e qualquer detalhe poderia decidir o destino da partida. Na volta, o {time_vencedor} voltou mais agressivo e começou a impor seu ritmo. O {time_perdedor} respondeu com personalidade, mas aos poucos a diferença começou a aparecer. O quarto final foi de tirar o fôlego. Cada bola disputada, cada falta, cada arremesso carregava um peso enorme. Quando o tempo acabou, o {time_vencedor} celebrou uma vitória construída com suor e inteligência.',
        'en' => 'On this special {period}, the {time_vencedor} and the {time_perdedor} wrote another emotional chapter of the season. What we saw on the floor was high-level basketball from start to finish. The final score of {placar} is just the number that summarizes a contest full of comebacks, big individual performances and a lot of heart. It was clear from the opening minutes that neither side was willing to back down. Three-pointers, fast breaks and intense defense kept the crowd on constant alert. At halftime the game was still wide open, and any single detail could decide its outcome. After the break the {time_vencedor} came back more aggressive and started imposing its pace. The {time_perdedor} responded with personality, but little by little the gap began to show. The final quarter was breathless. Every contested ball, every foul, every shot carried enormous weight. When the clock ran out, the {time_vencedor} celebrated a win built on sweat and intelligence.',

        ],

        // Variação 4 – Tom de espetáculo
        [
            'pt' => 'O ginásio viveu uma verdadeira festa nesta {periodo}. O duelo entre {time_vencedor} e {time_perdedor} superou todas as expectativas e entregou um espetáculo completo. Com o placar fechado em {placar}, a partida teve de tudo: sequências ofensivas brilhantes, defesas heroicas e momentos de pureza técnica que fizeram o público se levantar da cadeira. Os dois times entraram em quadra com fome de vitória e mostraram isso em cada jogada. O primeiro tempo já havia sido intenso, mas foi no segundo que o jogo realmente explodiu. O {time_vencedor} encontrou o ritmo certo e começou a ditar o ritmo, enquanto o {time_perdedor} lutava para não deixar a diferença aumentar. Nos minutos finais a tensão chegou ao máximo. Cada posse de bola valia ouro e qualquer descuido poderia ser fatal. O {time_vencedor}, no entanto, manteve a frieza e soube administrar a vantagem até o apito final.',
        'en' => 'The arena lived for a real celebration on this {period}. The duel between the {time_vencedor} and the {time_perdedor} exceeded every expectation and delivered a complete show. With the score locked at {placar}, the game had everything: brilliant offensive runs, heroic defensive plays and moments of pure technique that had the crowd on its feet. Both teams came out hungry to win and showed it on every possession. The first half was already intense, but it was in the second quarter that the game truly exploded. The {time_vencedor} found the right rhythm and started dictating the pace, while the {time_perdedor} fought hard to stop the gap from growing. In the closing minutes the tension reached its peak. Every possession was worth gold and any slip could have been fatal. The {time_vencedor}, however, stayed composed and knew how to manage the lead until the final buzzer.',

        ],

        // Variação 5 – Estilo direto e impactante
        [
            'pt' => 'Foi uma {periodo} de puro espetáculo. O {time_vencedor} derrotou o {time_perdedor} por {placar} em um jogo que terá lugar garantido entre os melhores da temporada. Do primeiro ao último segundo a intensidade não baixou. As duas equipes trocaram cestas, erros e grandes momentos de forma quase ininterrupta. O placar ficou equilibrado por longos períodos, mantendo a torcida em suspense constante. Depois do intervalo o {time_vencedor} elevou o nível e começou a controlar melhor as ações. Mesmo assim o {time_perdedor} não se entregou e continuou brigando por cada bola. O último quarto foi o ponto alto da partida. Sequências de três pontos, rebotes ofensivos e defesas desesperadas criaram um clima de decisão. Quando o tempo acabou, o {time_vencedor} tinha conquistado uma vitória merecida emocionante.',
        'en' => 'It was an {period} of pure showmanship. The {time_vencedor} defeated the {time_perdedor} by {placar} in a game that will secure a place among the best of the season. The intensity never dropped from the opening tip to the final second. The two teams traded baskets, mistakes and huge moments almost without interruption. The score stayed balanced for long stretches, keeping the crowd in constant suspense. After halftime the {time_vencedor} raised its level and started controlling the game better. Even so the {time_perdedor} never gave up and kept battling for every ball. The fourth quarter was the high point of the game. Three-point runs, offensive rebounds and desperate defense created a deciding atmosphere. When time ran out, the {time_vencedor} had earned a well-deserved and emotional win.',
        ],
    ];

    /**
     * Resolve o locale pedido, caindo no PT para qualquer valor desconhecido.
     */
    public function locale(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return array_key_exists($locale, self::COLUMNS) ? $locale : self::DEFAULT_LOCALE;
    }

    /**
     * Coluna de games que guarda a analise deste idioma.
     */
    public function column(?string $locale = null): string
    {
        return self::COLUMNS[$this->locale($locale)];
    }

    /**
     * Descobre qual variante gerou um texto ja salvo.
     *
     * Sem isso o PT e o EN do mesmo jogo saiam de variantes diferentes e trocar
     * de idioma trocava a historia, nao so o idioma.
     */
    public function variantOf(string $text, ?string $locale = null): ?int
    {
        $locale = $this->locale($locale);

        $markers = '{periodo}|{period}|{time_vencedor}|{time_perdedor}|{placar}';

        foreach ($this->templates as $key => $pair) {
            // Cortar o template direto quebrava placeholders pela metade
            // ("{time_vencede"), entao o padrao e montado segmento a segmento
            // e so um literal ja inteiro entra na conta de tamanho.
            $parts = preg_split('/('.$markers.')/', $pair[$locale], -1, PREG_SPLIT_DELIM_CAPTURE);

            $pattern = '';
            $len = 0;

            foreach ($parts as $part) {
                if ($len >= 90) {
                    break;
                }

                if (preg_match('/^\\{.*\\}$/', $part)) {
                    $pattern .= $part === '{placar}' ? '.+?' : '.*?';

                    continue;
                }

                $take = mb_substr($part, 0, 90 - $len);
                $pattern .= preg_quote($take, '/');
                $len += mb_strlen($take);
            }

            // Compara contra o texto inteiro: truncar o texto e o padrao no mesmo
            // tamanho quebrava, porque os curingas do padrao consomem parte do
            // texto que o limite de 90 chars do literal nao previa.
            if (preg_match('/^'.$pattern.'/u', $text)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Generate analysis text for a game, in the given locale.
     *
     * @param string $gameTime e.g. "2025-11-15 19:30:00"
     * @param int|null $variant fixa a variante; sem isso sorteia uma
     */
    public function generate(string $winner, string $loser, string $score, string $gameTime, ?string $locale = null, ?int $variant = null): string
    {
        $locale = $this->locale($locale);

        $hour = (int) date('H', strtotime($gameTime));
        [$day, $night] = self::PERIODS[$locale];
        $period = $hour < 17 ? $day : $night;

        $variants = array_keys($this->templates);
        $key = $variant !== null && array_key_exists($variant, $this->templates)
            ? $variant
            : $variants[array_rand($variants)];

        $template = $this->templates[$key][$locale];

        return str_replace(
            ['{time_vencedor}', '{time_perdedor}', '{placar}', '{periodo}', '{period}'],
            [$winner, $loser, $score, $day, $period],
            $template
        );
    }

    /**
     * Return the stored analysis for the given locale, generating and persisting
     * it only the first time. Picking a template at random on every request
     * made the text change each time the page was opened, which is what this
     * now prevents.
     *
     * @return string|null null when the game has no resolvable teams
     */
    public function forGame(Game $game, ?string $locale = null): ?string
    {
        $locale = $this->locale($locale);
        $column = self::COLUMNS[$locale];

        if (filled($game->{$column})) {
            return $game->{$column};
        }

        $winner = $game->home_score > $game->away_score ? $game->homeTeam : $game->awayTeam;
        $loser = $game->home_score > $game->away_score ? $game->awayTeam : $game->homeTeam;

        if (! $winner || ! $loser) {
            return null;
        }

        $analysis = $this->generate(
            $winner->name,
            $loser->name,
            $game->home_score . '-' . $game->away_score,
            $game->game_date,
            $locale,
            $this->variantFor($game, $locale)
        );

        $game->forceFill([$column => $analysis])->save();

        return $analysis;
    }

    /**
     * Variante a reusar quando o outro idioma ja tem texto salvo.
     *
     * Traduzir e manter a mesma variante faz o jogo contar a mesma historia nos
     * dois idiomas. Quando o PT nao existe ainda, deixa a sorteio normal agir.
     */
    private function variantFor(Game $game, string $locale): ?int
    {
        $other = $locale === self::DEFAULT_LOCALE
            ? self::COLUMNS['en']
            : self::COLUMNS[self::DEFAULT_LOCALE];

        $stored = $game->{$other} ?? null;

        return filled($stored) ? $this->variantOf($stored) : null;
    }

    /**
     * Analise ja salva em algum idioma, para nao regenerar a toa.
     */
    public function forGameAnyLocale(Game $game): ?string
    {
        foreach (self::COLUMNS as $column) {
            if (filled($game->{$column})) {
                return $game->{$column};
            }
        }

        return null;
    }

    /**
     * Backfill every finished game that still has no stored analysis, in every
     * locale. Without an explicit locale list only PT is filled, which is what
     * the original command did.
     *
     * @param  array<int,string>|null  $locales
     */
    public function persistMissing(?callable $onEach = null, ?array $locales = null): int
    {
        $count = 0;

        $locales ??= [self::DEFAULT_LOCALE];

        Game::where('status', 'finished')
            ->where(function ($query) use ($locales) {
                $query->where(function ($inner) use ($locales) {
                    foreach ($locales as $locale) {
                        $column = self::COLUMNS[$this->locale($locale)];

                        $inner->orWhereNull($column)->orWhere($column, '');
                    }
                });
            })
            ->with('homeTeam', 'awayTeam')
            ->orderBy('id')
            ->chunkById(100, function ($games) use (&$count, $onEach, $locales) {
                foreach ($games as $game) {
                    $touched = false;

                    foreach ($locales as $locale) {
                        if ($this->forGame($game, $locale) !== null) {
                            $touched = true;
                        }
                    }

                    if (! $touched) {
                        continue;
                    }

                    $count++;

                    if ($onEach) {
                        $onEach($game);
                    }
                }
            });

        return $count;
    }
}