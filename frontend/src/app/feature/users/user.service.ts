import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../../environments/environment.local';
import {Observable} from 'rxjs';
import {UsersListItem} from './lecturers-list/users-list-datasource';


@Injectable({providedIn: 'root'})
export class UserService {
    constructor(private http: HttpClient) {
    }

    getAll(): Observable<UsersListItem[]> {
        return this.http.get<UsersListItem[]>(`${environment.apiUrl}/lecturers`);
    }

    upload(fd: FormData) {
        return this.http.post(`${environment.apiUrl}/import-lecturers`, fd);
    }
}
