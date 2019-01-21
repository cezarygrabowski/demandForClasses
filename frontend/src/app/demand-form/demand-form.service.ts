import {Injectable} from '@angular/core';
import {environment} from '../../environments/environment.local';
import {HttpClient} from '@angular/common/http';
import {DemandElement} from '../demand-list/demand-element';
import {Observable, Subscription} from 'rxjs';
import {Router} from "@angular/router";

@Injectable({
    providedIn: 'root'
})
export class DemandFormService {
    public roles;

    constructor(
        private http: HttpClient,
        private router: Router
    ) {

    }

    getDemandDetails(id: string): Observable<DemandElement> {
        return this.http.get<DemandElement>(`${environment.apiUrl}/demands/details/${id}`);
    }

    getLecturers(demandId: string) {
        return this.http.get(`${environment.apiUrl}/lecturers/${demandId}`);
    }

    getBuildings(): Observable<any> {
        return this.http.get(`${environment.apiUrl}/demands/buildings`);
    }

    getRoles() {
        return this.http.get(`${environment.apiUrl}/lecturer-roles`);
    }

    updateDemand(demand: DemandElement): Subscription {
        return this.http.post(`http://127.240.0.2/demands/update/${demand.id}`, demand).subscribe(
            response => this.router.navigate(['/lista-zapotrzebowan'],
            err => console.log(err))
        );
    }

    doesUserHaveRoles(roles: Array<string>): boolean {
        let found = false;
        if (this.roles) {
            found = this.roles.some(r => roles.indexOf(r) >= 0);
        }
        return found;
    }

    apiRequest(demand: DemandElement): Promise<any> {
        // this.http.post<any>(`${environment.apiUrl}/demands/update/${demand.id}`, demand).subscribe(response => console.log(response.json()));
        return this.http.post<any>(`${environment.apiUrl}/api`, demand).toPromise()
            .then(value => console.log(value))
            .catch(error => console.log(error));
    }

    setAutomaticallySendToPlanners(checked: boolean) {
        return this.http.post(`http://127.240.0.2/lecturers/automatically-send-to-planners`, checked).subscribe(
            response => console.log(response),
            err => console.log(err)
        );
    }

    exportDemands() {
        return this.http.post(`${environment.apiUrl}/demands/export`, {}, {responseType: 'blob'});
    }

    cancelDemand(demand: DemandElement): Subscription {
        return this.http.post(`${environment.apiUrl}/demands/cancel/${demand.id}`, {}).subscribe(
            response => this.router.navigate(['/lista-zapotrzebowan'],
            err => console.log(err)
        ));
    }
}
