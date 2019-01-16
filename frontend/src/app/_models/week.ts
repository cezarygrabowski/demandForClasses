export class Week {
    static WEEK_TRANSLATION = {
        '1': 'Tydzień 1',
        '2': 'Tydzień 2',
        '3': 'Tydzień 3',
        '4': 'Tydzień 4',
        '5': 'Tydzień 5',
        '6': 'Tydzień 6',
        '7': 'Tydzień 7',
        '8': 'Tydzień 8',
        '9': 'Tydzień 9',
        '10': 'Tydzień 10',
        '11': 'Tydzień 11',
        '12': 'Tydzień 12',
        '13': 'Tydzień 13',
        '14': 'Tydzień 14',
        '15': 'Tydzień 15'
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
