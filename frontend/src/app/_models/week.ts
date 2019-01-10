export class Week {
    static readonly WEEK_TRANSLATION = {
        week1: 'Tydzień 1',
        week2: 'Tydzień 2',
        week3: 'Tydzień 3',
        week4: 'Tydzień 4',
        week5: 'Tydzień 5',
        week6: 'Tydzień 6',
        week7: 'Tydzień 7',
        week8: 'Tydzień 8',
        week9: 'Tydzień 9',
        week10: 'Tydzień 10',
        week11: 'Tydzień 11',
        week12: 'Tydzień 12',
        week13: 'Tydzień 13',
        week14: 'Tydzień 14',
        week15: 'Tydzień 15'
    };
    week: string;
    hours: number;
    constructor(
        week: string,
        hours: number
    ) {
        this.week = week;
        this.hours = hours;
    }
}
