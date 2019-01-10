import {Room} from './room';

export interface Building {
    id: number;
    name: string;
    rooms: Room[];
}
