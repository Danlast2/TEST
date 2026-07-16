<?php

namespace App\Enums;

enum EventTag: string
{
    case DISCUSSION = 'discussion';
    case BOOK_OF_THE_MONTH = 'book_of_the_month';
    case THEMATIC_CYCLES = 'thematic_cycles';
    case QUIZ = 'quiz';
    case ROLE_PLAY = 'role_play';
    case QUEST = 'quest';
    case FANFICTION = 'fanfiction';
    case CREATIVE_WRITING = 'creative_writing';
    case READ_ALOUD = 'read_aloud';
    case THEATRICAL_READING = 'theatrical_reading';
    case POETRY = 'poetry';
    case UNUSUAL_PLACES = 'unusual_places';
    case LECTURE = 'lecture';
    case DISCUSSION_CIRCLE = 'discussion_circle';
    case DEBATE = 'debate';
    case MEET_AUTHOR = 'meet_author';
    case BOOK_SWAP = 'book_swap';
    case BOOK_TRIPS = 'book_trips';

    public function label(): string
    {
        return match ($this) {
            self::DISCUSSION => 'Обсуждение прочитанной книги',
            self::BOOK_OF_THE_MONTH => 'Книга месяца',
            self::THEMATIC_CYCLES => 'Тематические циклы',
            self::QUIZ => 'Литературные квизы и викторины',
            self::ROLE_PLAY => 'Ролевые игры по мотивам книг',
            self::QUEST => 'Литературные квесты',
            self::FANFICTION => 'Написание фанфиков',
            self::CREATIVE_WRITING => 'Мастер-классы по креативному письму',
            self::READ_ALOUD => 'Громкие чтения',
            self::THEATRICAL_READING => 'Театрализованные чтения',
            self::POETRY => 'Поэтические вечера',
            self::UNUSUAL_PLACES => 'Чтения в необычных местах',
            self::LECTURE => 'Лекции о литературе',
            self::DISCUSSION_CIRCLE => 'Тематические дискуссии',
            self::DEBATE => 'Дебаты по героям и идеям',
            self::MEET_AUTHOR => 'Встречи с авторами',
            self::BOOK_SWAP => 'Книжные обмены',
            self::BOOK_TRIPS => 'Совместные походы в книжные магазины и музеи',
        };
    }

    public static function options(): array
    {
        return array_reduce(self::cases(), function (array $carry, self $case): array {
            $carry[$case->value] = $case->label();

            return $carry;
        }, []);
    }
}
