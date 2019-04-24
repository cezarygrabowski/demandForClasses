import {Injectable} from '@angular/core';
import {environment} from '../../../environments/environment.local';
import {HttpClient} from '@angular/common/http';
import {Observable, Subscription} from 'rxjs';
import {DemandListElement} from './demandListElement';
import {Demand} from "./interfaces/form/demand";
import {Lecturer} from "./interfaces/form/lecturer";
import {Place} from "./interfaces/form/place";

@Injectable({
  providedIn: 'root'
})
export class DemandService {

  constructor(
    private http: HttpClient
  ) {
  }

  public getDemands(): Observable<DemandListElement[]> {
    return this.http.get<DemandListElement[]>(`${environment.apiUrl}/demands`);
  }

  getDemandDetails(uuid: string): Observable<Demand> {
    return this.http.get<Demand>(`${environment.apiUrl}/details/${uuid}`);
  }

  getLecturers(demandUuid: string): Observable<Lecturer[]> {
    return this.http.get<Lecturer[]>(`${environment.apiUrl}/lecturers/${demandUuid}`);
  }

  getPlaces(): Observable<Place[]> {
    return this.http.get<Place[]>(`${environment.apiUrl}/places`);
  }

  getQualifiedLecturers(subjectName: string): Observable<Lecturer[]> {
    return this.http.get<Lecturer[]>(`${environment.apiUrl}/lecturers/${subjectName}`);
  }

  //
  // getRoles() {
  //   return this.http.get(`${environment.apiUrl}/lecturer-roles`);
  // }
  //
  // updateDemand(demand: Demand): Subscription {
  //   return this.http.post(`http://127.240.0.2/demands/update/${demand.id}`, demand).subscribe(
  //       response => this.router.navigate(['/lista-zapotrzebowan']),
  //       err => console.log(err)
  //   );
  // }
  //
  // doesUserHaveRoles(roles: Array<string>): boolean {
  //   let found = false;
  //   if (this.roles) {
  //     found = this.roles.some(r => roles.indexOf(r) >= 0);
  //   }
  //   return found;
  // }
  //
  // setAutomaticallySendToPlanners(checked: boolean) {
  //   return this.http.post(`http://127.240.0.2/lecturers/automatically-send-to-planners`, checked).subscribe(
  //       response => console.log(response),
  //       err => console.log(err)
  //   );
  // }
  //
  // exportDemands() {
  //   return this.http.post(`${environment.apiUrl}/demands/export`, {}, {responseType: 'blob'});
  // }
  //
  // declineDemand(demand: DemandListElement): Subscription {
  //   return this.http.post(`${environment.apiUrl}/demands/cancel/${demand.uuid}`, {}).subscribe(
  //       response => this.router.navigate(['/lista-zapotrzebowan']),
  //       (err) => console.log(err)
  //   );
  // }
  updateDemand(demand: Demand): Observable<any> {
    return this.http.put(`${environment.apiUrl}/update/${demand.uuid}`, { demand });
  }

  declineDemand(uuid: string): Observable<any>  {
    return this.http.put(`${environment.apiUrl}/decline/${uuid}`, { });
  }

  acceptDemand(uuid: string) {
    return this.http.put(`${environment.apiUrl}/accept/${uuid}`, { });
  }

  downloadDemand(uuid: string): Observable<any> {
    return this.http.get(`${environment.apiUrl}/download-demand/${uuid}`, {responseType: 'blob'});
  }
}
