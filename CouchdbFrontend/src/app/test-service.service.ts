import { HttpClient,HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';


@Injectable({
  providedIn: 'root'
})
export class TestServiceService {
  public baseUrl:any = "http://localhost/couchDbProject";

  constructor(private http:HttpClient) {  }

/*   httpOptions = {
    headers: new HttpHeaders({
      //"content-Type": "application/json"
      'content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
      //"Authorization": `Bearer ${this.idToken}`,
    }),

  }; */


  fetchData(path:any){
    return this.http.get<any>(this.baseUrl + path);
  }

  dataOpr(obj:any,path:any){
    return this.http.post<any>(this.baseUrl + path, obj);
  }


}
