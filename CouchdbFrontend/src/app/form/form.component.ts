import { Component,ElementRef, OnInit, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { TestServiceService } from '../test-service.service';
declare var bootstrap: any;

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {
  @ViewChild('f') testForm!: NgForm;
  @ViewChild('editModal') editModal!: ElementRef;

  public colDisplay:any = [];
  public rowData:any;
  public noData:boolean=false;
  public succ:boolean=false;
  public insSucc:boolean = false;
  public first_name:any="";
  public last_name:any="";
  public email:any="";
  public state:any="";
  public age:any;
  public phone_number:any;
  public all_data:any;

  constructor(private service:TestServiceService) {}

  ngOnInit() {
    this.get_alldata(); 
  }

  get_alldata(){
    this.noData = true
    this.service.fetchData('/fetch-all-data').subscribe(
      (res : any)=>{
     if(res.status == 'succ'){
      this.noData = false;
      this.all_data = res.data;
      this.all_data.forEach((element:any) => {
        Object.keys(element).forEach((key:string) => {
        ['_id','_rev'].includes(key) ? null :(this.colDisplay.includes(key)? null: this.colDisplay.push(key));
        });
      });

      // this.all_data.sort((a:any, b:any) => b._id - a._id);

      // console.log(this.colDisplay);
    }else{
      console.log(res.msg);
    }
  });  
  }


  delete_data(id:any,revId:any){
    let obj={'id':id,
            'revId' :revId 
            };
    this.service.dataOpr(obj , '/delete-data').subscribe( 
      (res)=> {

        if(res.status == "succ"){
          this.get_alldata();
        }else{
          console.log(res.msg);
        }
    }
    );

  }


  update_data(data:any){
    // console.log(data);
    let obj = data;
    this.service.dataOpr(obj , '/update-data').subscribe((response)=>{
      
      // console.log("Update Response:", response);
      if(response.status=="succ"){
        // console.log(this.editModal);
        // this.editModal.close();
        /* const modalElement = this.editModal.nativeElement as HTMLElement;
        console.log('Modal Element:', modalElement); // Debugging
        const bootstrapModal = new bootstrap.Modal(modalElement); // Initialize Bootstrap modal
        console.log('Bootstrap Modal Object:', bootstrapModal); // Debugging
        bootstrapModal.dismiss(); */
        this.succ = true;

        this.get_alldata();

      }else{
        console.log(response.msg);
      }
    }

    );
  }


  editRow(data:any){
    this.succ = false;
    this.rowData = {...data}; // creates a sepearate copy  of the object so that changes don't affect actual data
  }

  insertData(){
    let obj = this.testForm.value;
    this.service.dataOpr(obj, "/insert-data").subscribe((response) => {
      
      if(response.status=="succ"){
          this.insSucc =  true;
          this.get_alldata();
          setTimeout(()=> {
            this.insSucc = false ;
           }, 3000);
      }else{
        console.log(response.msg);
      }
      
    });

  }



}
