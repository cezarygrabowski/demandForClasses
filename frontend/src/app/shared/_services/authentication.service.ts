﻿import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {BehaviorSubject, Observable} from 'rxjs';
import {map} from 'rxjs/operators';
import {User} from '../_models';
import {environment} from '../../../environments/environment.local';
import * as jwt_decode from "jwt-decode";

@Injectable({providedIn: 'root'})
export class AuthenticationService {
    private currentUserSubject: BehaviorSubject<User>;
    public currentUser: Observable<User>;

    constructor(private http: HttpClient) {
        this.currentUserSubject = new BehaviorSubject<User>(JSON.parse(localStorage.getItem('currentUser')));
        this.currentUser = this.currentUserSubject.asObservable();
    }

    public get currentUserValue(): User {
        return this.currentUserSubject.value;
    }

    login(username: string, password: string) {
        return this.http.post<any>(`${environment.apiUrl}/login_check`, {username, password})
            .pipe(map(user => {
                // login successful if there's a jwt token in the response
                if (user && user.token) {
                    // store user details and jwt token in local storage to keep user logged in between page refreshes
                    localStorage.setItem('currentUser', JSON.stringify(user));
                    this.currentUserSubject.next(user);
                }

                return user;
            }));
    }

    logout() {
        // remove user from local storage to log user out
        localStorage.removeItem('currentUser');
        this.currentUserSubject.next(null);
    }

    isLoggedIn() {
        if (localStorage.getItem('currentUser')) {
            return true;
        }

        return false;
    }

    private getUserRole() {
        const roles = jwt_decode(JSON.parse(localStorage.getItem('currentUser')).token).roles;
        return roles[0];
    }

    getUsername() {
        const username = jwt_decode(JSON.parse(localStorage.getItem('currentUser')).token).username;
        return username;
    }

    isPlanner() {
        return this.getUserRole() === 'ROLE_PLANNER';
    }

    isDistrictManager() {
        return this.getUserRole() === 'ROLE_DISTRICT_MANAGER';
    }

    isLecturer() {
        return this.getUserRole() === 'ROLE_LECTURER';
    }
}
