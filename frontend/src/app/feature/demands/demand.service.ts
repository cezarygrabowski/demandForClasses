import { Injectable } from '@angular/core';
import {environment} from "../../../environments/environment.local";
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";
import {Demand} from "../demand-list/demand";

@Injectable({
  providedIn: 'root'
})
export class DemandService {

  constructor(private http: HttpClient) { }

  public getDemands(): Observable<Demand[]> {
    console.log("dsadas");
    return this.http.get<Demand[]>(`${environment.apiUrl}/demands`);
  }

  exportDemands(uuids: []) {
    return this.http.post(`${environment.apiUrl}/export`, {uuids: uuids});
  }
}
