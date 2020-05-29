<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Employee;
use App\Department;

class EmployeeController extends Controller
{ 
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $department = Department::orderBy('name') -> get();
        $employee = Employee::orderBy('name')->paginate(20);

        return view("employee.index",['employee' => $employee,'department' => $department]);
    }

    public function create()
    {
        $department = Department::all();
        
        if(count($department) <  1){
            return redirect("/department")->with("error","You must create a department before creating an employee");
       }
        return view("employee.create",['department' => $department]);
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $department = Department::all();
        return view("employee.edit",['employee' => $employee],['department' => $department]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'department' =>  'required',  
            'location' =>  'required|max:100',  
            'telephone' =>  'required|max:15',  
            'salary' =>  'required',
            'cover_image' => 'image|nullable|max:1999',
            'Job_grade' =>  'required',
            'paterson_grade' =>  'required',
            'min_salary' =>  'required',
            'max_salary' =>  'required',
            'salary_pm' =>  'required',
            'transport_allowance' =>  'required',
            'accomodation_allowance' =>  'required',
            'entertainment_allowance' =>  'required',
            'lunch_allowance' =>  'required',
            'cash_allowance' =>  'required|max:50',
            'amount' =>  'required',
            'allowancepaid' =>  'required',
            'taxperdiem' =>  'required',
            'benefits_payed' =>  'required',
        
        ]);

        // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $employee = new Employee();

        $employee->name = request('name');
        $employee->department = request('department');
        $employee->location = request('location');
        $employee->telephone = request('telephone');
        $employee->salary = request('salary');
        $employee->cover_image = $fileNameToStore;
        $employee->Job_grade = request('Job_grade');
        $employee->paterson_grade = request('paterson_grade');
        $employee->min_salary = request('min_salary');
        $employee->max_salary = request('max_salary');
        $employee->salary_pm = request('salary_pm');
        $employee->transport_allowance = request('transport_allowance');
        $employee->accomodation_allowance = request('accomodation_allowance');
        $employee->entertainment_allowance = request('entertainment_allowance');
        $employee->lunch_allowance = request('lunch_allowance');
        $employee->cash_allowance = request('cash_allowance');
        $employee->amount = request('amount');
        $employee->allowancepaid = request('allowancepaid');
        $employee->taxperdiem = request('taxperdiem');
        $employee->benefits_payed = request('benefits_payed');

        
        $employee->save();

        return redirect("/employee")->with('success',"Employee Created Successfully");
    }
    

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view("employee.show",['employee' => $employee]);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        if($employee->cover_image != 'noimage.jpg'){
            // Delete Image
            Storage::delete('public/cover_images/'.$employee->cover_image);
        }
        
        return redirect("/employee")->with("success","Employee Deleted Successfully");
    }

    public function update_record(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'department' =>  'required',  
            'location' =>  'required|max:100',  
            'telephone' =>  'required|max:15',  
            'salary' =>  'required',
            'cover_image' => 'image|nullable|max:1999',
            'Job_grade' =>  'required',
            'paterson_grade' =>  'required',
            'min_salary' =>  'required',
            'max_salary' =>  'required',
            'salary_pm' =>  'required',
            'transport_allowance' =>  'required',
            'accomodation_allowance' =>  'required',
            'entertainment_allowance' =>  'required',
            'lunch_allowance' =>  'required',
            'cash_allowance' =>  'required|max:50',
            'amount' =>  'required',
            'allowancepaid' =>  'required',
            'taxperdiem' =>  'required',
            'benefits_payed' =>  'required'
        
        ]);

        $employee = Employee::findOrFail($id);
        // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
            // Delete file if exists
            Storage::delete('public/cover_images/'.$employee->cover_image);
        }

        $employee->name = request('name');
        $employee->department = request('department');
        $employee->location = request('location');
        $employee->telephone = request('telephone');
        $employee->salary = request('salary');
        $employee->Job_grade = request('Job_grade');
        $employee->paterson_grade = request('paterson_grade');
        $employee->min_salary = request('min_salary');
        $employee->max_salary = request('max_salary');
        $employee->salary_pm = request('salary_pm');
        $employee->transport_allowance = request('transport_allowance');
        $employee->accomodation_allowance = request('accomodation_allowance');
        $employee->entertainment_allowance = request('entertainment_allowance');
        $employee->lunch_allowance = request('lunch_allowance');
        $employee->cash_allowance = request('cash_allowance');
        $employee->amount = request('amount');
        $employee->allowancepaid = request('allowancepaid');
        $employee->taxperdiem = request('taxperdiem');
        $employee->benefits_payed = request('benefits_payed');
        if($request->hasFile('cover_image')){
            $employee->cover_image = $fileNameToStore;
        }

        $employee->save(); //this will UPDATE the record

        return redirect("/employee")->with("success","Account was updated successfully");
    }

    public function single($id)
    {
        $employee = Employee::where('department',$id)->orderBy('name') -> paginate(20);
        $department = Department::orderBy('name') -> get();

        return view('employee.single',['employee' => $employee,'department' => $department]);
    }

    public function pay($id)
    {
        $department = Department::orderBy('name') -> get();
        $employee = Employee::findOrFail($id);
        return view("employee.pay",['employee' => $employee,'department' => $department]);
    }    
}
