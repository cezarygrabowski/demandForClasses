import { Injectable } from '@angular/core';
import {environment} from '../../../environments/environment.local';
import {HttpClient} from '@angular/common/http';
import {Observable, Subscription} from 'rxjs';
import {DemandListElement} from './demandListElement';
import {Router} from '@angular/router';
import {Demand} from "./demand";

@Injectable({
  providedIn: 'root'
})
export class DemandService {

  constructor(
      private http: HttpClient,
      private router: Router
      ) { }

  public getDemands(): Observable<DemandListElement[]> {
    console.log('dsadas');
    return this.http.get<DemandListElement[]>(`${environment.apiUrl}/demands`);
  }

  //
  // getDemandDetails(id: string): Observable<Demand> {
  //   return this.http.get<Demand>(`${environment.apiUrl}/demands/details/${id}`);
  // }
  //
  // getLecturers(demandId: string) {
  //   return this.http.get(`${environment.apiUrl}/lecturers/${demandId}`);
  // }
  //
  // getBuildings(): Observable<any> {
  //   return this.http.get(`${environment.apiUrl}/demands/buildings`);
  // }
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
  // cancelDemand(demand: DemandListElement): Subscription {
  //   return this.http.post(`${environment.apiUrl}/demands/cancel/${demand.id}`, {}).subscribe(
  //       response => this.router.navigate(['/lista-zapotrzebowan']),
  //       (err) => console.log(err)
  //   );
  // }
}
