<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Str;

class NewsGenerator
{
    private array $templates = [
        'Foi uma {periodo} que ninguém que estava no ginásio vai esquecer tão cedo. O confronto entre o {time_vencedor} e o {time_perdedor} entregou tudo que um grande jogo de basquete precisa ter: intensidade do primeiro ao último segundo, jogadas espetaculares, reviravoltas constantes e uma tensão que só aumentava a cada posse de bola. O placar final de {placar} não conta nem a metade da história do que aconteceu dentro de quadra. Os dois times entraram em campo com mentalidade de final e mostraram isso desde o primeiro período, trocando cestas de três, defesas agressivas e rebotes decisivos. No segundo quarto o jogo já tinha virado um verdadeiro duelo de personalidades, com o placar apertado e o público em pé a cada jogada importante. No intervalo o {time_vencedor} ainda precisava encontrar respostas, mas voltou do vestiário com outra atitude. A defesa apertou, as bolas de três começaram a cair e a equipe encontrou ritmo ofensivo. Mesmo assim o {time_perdedor} não se entregou e respondeu com sequências próprias, mantendo a partida aberta até os minutos finais. Foi no último quarto que a diferença realmente apareceu. O {time_vencedor} mostrou frieza, aproveitou os erros do adversário e fechou o jogo com autoridade. O apito final selou uma vitória merecida e emocionante, daquelas que ficam na memória da torcida. Um espetáculo completo de basquete, com tudo o que se espera de um grande confronto. O {time_vencedor} saiu de campo como merecido vencedor de uma {periodo} histórica.',

        'Quem acompanhou o embate entre {time_vencedor} e {time_perdedor} nesta {periodo} teve a sensação de estar assistindo a um jogo de mata-mata. A partida foi marcada por um ritmo alucinante desde a bola ao alto, com as duas equipes trocando golpes sem dar trégua. O placar de {placar} esconde a verdadeira batalha que aconteceu em cada posse. Defesas travadas, arremessos de três em sequência e rebotes disputados com unhas e dentes transformaram o ginásio em um caldeirão. No segundo quarto o jogo já estava equilibrado e qualquer erro poderia custar caro. Depois do intervalo o {time_vencedor} elevou o nível defensivo e começou a controlar melhor o ritmo. Mesmo sob pressão constante do {time_perdedor}, a equipe manteve a calma e encontrou soluções ofensivas nos momentos certos. O último quarto foi puro drama. Cada cesta era celebrada como se fosse a última, e a torcida não parava de gritar. Quando o apito final soou, o {time_vencedor} confirmou a vitória merecida e emocionante. Uma {periodo} para ficar gravada na memória de todos que tiveram a chance de assistir.',

        'Nesta {periodo} especial, o {time_vencedor} e o {time_perdedor} escreveram mais um capítulo emocionante da temporada. O que se viu em quadra foi basquete de alto nível do começo ao fim. O placar final de {placar} é apenas o número que resume uma disputa cheia de reviravoltas, grandes individualidades e muito coração. Desde os primeiros minutos ficou claro que nenhum dos lados estava disposto a ceder. Cestas de três, contra-ataques rápidos e defesas intensas mantiveram o público em estado de alerta constante. No intervalo o jogo ainda estava completamente aberto, e qualquer detalhe poderia decidir o destino da partida. Na volta, o {time_vencedor} voltou mais agressivo e começou a impor seu ritmo. O {time_perdedor} respondeu com personalidade, mas aos poucos a diferença começou a aparecer. O quarto final foi de tirar o fôlego. Cada bola disputada, cada falta, cada arremesso carregava um peso enorme. Quando o tempo acabou, o {time_vencedor} celebrou uma vitória construída com suor e inteligência. Uma {periodo} inesquecível de basquete puro.',

        'O ginásio viveu uma verdadeira festa nesta {periodo}. O duelo entre {time_vencedor} e {time_perdedor} superou todas as expectativas e entregou um espetáculo completo. Com o placar fechado em {placar}, a partida teve de tudo: sequências ofensivas brilhantes, defesas heroicas e momentos de pureza técnica que fizeram o público se levantar da cadeira. Os dois times entraram em quadra com fome de vitória e mostraram isso em cada jogada. O primeiro tempo já havia sido intenso, mas foi no segundo que o jogo realmente explodiu. O {time_vencedor} encontrou o ritmo certo e começou a ditar o ritmo, enquanto o {time_perdedor} lutava para não deixar a diferença aumentar. Nos minutos finais a tensão chegou ao máximo. Cada posse de bola valia ouro e qualquer descuido poderia ser fatal. O {time_vencedor}, no entanto, manteve a frieza e soube administrar a vantagem até o apito final. Foi uma {periodo} de basquete de altíssimo nível, daquelas que fazem o fã se apaixonar ainda mais pelo esporte.',

        'Foi uma {periodo} de puro espetáculo. O {time_vencedor} derrotou o {time_perdedor} por {placar} em um jogo que terá lugar garantido entre os melhores da temporada. Do primeiro ao último segundo a intensidade não baixou. As duas equipes trocaram cestas, erros e grandes momentos de forma quase ininterrupta. O placar ficou equilibrado por longos períodos, mantendo a torcida em suspense constante. Depois do intervalo o {time_vencedor} elevou o nível e começou a controlar melhor as ações. Mesmo assim o {time_perdedor} não se entregou e continuou brigando por cada bola. O último quarto foi o ponto alto da partida. Sequências de três pontos, rebotes ofensivos e defesas desesperadas criaram um clima de decisão. Quando o tempo acabou, o {time_vencedor} tinha conquistado uma vitória merecida e emocionante. Uma {periodo} completa de basquete, com tudo que um grande jogo precisa ter.',
    ];

    public function generateForGame(Game $game): void
    {
        $homeWon = $game->home_score > $game->away_score;
        $winner = $homeWon ? $game->homeTeam : $game->awayTeam;
        $loser = $homeWon ? $game->awayTeam : $game->homeTeam;
        $winnerScore = $homeWon ? $game->home_score : $game->away_score;
        $loserScore = $homeWon ? $game->away_score : $game->home_score;
        $score = $winnerScore . '-' . $loserScore;

        $hour = (int) date('H', strtotime($game->game_date));
        $periodo = $hour < 17 ? 'tarde' : 'noite';

        $template = $this->templates[array_rand($this->templates)];

        $content = str_replace(
            ['{time_vencedor}', '{time_perdedor}', '{placar}', '{periodo}'],
            [$winner->name, $loser->name, $score, $periodo],
            $template
        );

        $title = $winner->name . ' vence ' . $loser->name . ' por ' . $score . ' em jogo eletrizante';

        $excerpt = $winner->name . ' derrotou ' . $loser->name . ' por ' . $score . ' em uma partida de puro espetáculo.';

        $admin = User::where('role', 'admin')->first() ?? User::first();

        Post::create([
            'user_id' => $admin->id,
            'game_id' => $game->id,
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => '<p>' . $content . '</p>',
            'excerpt' => $excerpt,
            'category' => 'noticia',
            'status' => 'published',
            'published_at' => $game->game_date,
            'views_count' => 0,
        ]);
    }

    public function generateForAllFinishedGames(): int
    {
        $games = Game::where('status', 'finished')
            ->with('homeTeam', 'awayTeam')
            ->get();

        $count = 0;
        foreach ($games as $game) {
            if (Post::where('game_id', $game->id)->exists()) {
                continue;
            }

            $this->generateForGame($game);
            $count++;
        }

        return $count;
    }
}
