import {Injectable} from '@angular/core';
import {environment} from '../../../environments/environment.local';
import {HttpClient} from '@angular/common/http';
import {Observable, Subscription} from 'rxjs';
import {DemandListElement} from './demandListElement';
import {Demand} from './interfaces/form/demand';
import {Lecturer} from './interfaces/form/lecturer';
import {Place} from './interfaces/form/place';

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

  getPlaces(): Observable<Place[]> {
    return this.http.get<Place[]>(`${environment.apiUrl}/places`);
  }

  getQualifiedLecturers(subjectName: string): Observable<Lecturer[]> {
    return this.http.get<Lecturer[]>(`${environment.apiUrl}/lecturers/${subjectName}`);
  }

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

  assignDemand(demand: Demand): Observable<any> {
    return this.http.put(`${environment.apiUrl}/assign-demand`, { demand });
  }

  exportDemands(uuids: Array<string>): Observable<any> {
    return this.http.put(`${environment.apiUrl}/export-demands`, { uuids }, {responseType: 'blob'});
  }
}
