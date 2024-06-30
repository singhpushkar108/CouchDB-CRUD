import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { FormComponent } from './form/form.component';

const routes:Routes = [
  {
    //if the path is not matching
    path: " ", redirectTo:'home', pathMatch:'full'
  },
  {
    // to home path
    path: "home", component: FormComponent
  },
  {
    path: '**' , component: FormComponent
  }

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

export const routedComponents = [FormComponent];