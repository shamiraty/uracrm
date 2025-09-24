
# SYSTEM REQUIREMENTS

## 1. OTP MESSAGE

| Requirement            | Status |
|------------------------|--------|
| Enquiry registration    | ✔️     |
| Enquiry / Processing    | ✔️     |
| Enquiry Approval        | ✔️     |
| Enquiry Rejection       | ✔️     |
| Enquiry Payment         | ✔️     |

## 2. ACTION MODAL POPUP

### **Loan Officer**

| Action       | Modal Popup | Alert |
|--------------|-------------|-------|
| View Loan    | ✔️          | ✔️    |
| Process Loan | ✔️          | ✔️    |
| Approve Loan | ✔️          | ✔️    |
| Reject Loan  | ✔️          | ✔️    |

**Others:**
- When Rejected: Disable Process & Approve
- When Approved: Disable Reject & Process
- When Processed: Enable Reject and Process

### **Accountant**

| Action           | Modal Popup | Alert |
|------------------|-------------|-------|
| Initiate Payment  | ✔️          | ❌    |
| Approve Payment   | ✔️          | ✔️    |
| Pay Payment       | ✔️          | ✔️    |

### **Mahusiano**

| Action          | Modal Popup | Alert |
|-----------------|-------------|-------|
| View Detail     | ❌          |       |
| Assign          | ✔️          | ❌    |
| Edit            | ❌          | ❌    |
| Delete          | ❌          | ❌    |

## 3. DATA TABLES

### **ENQUIRY MANAGEMENT**

| Enquiry Type                     | Datatable |
|-----------------------------------|-----------|
| Loan application Enquiries        | ❌        |
| Share enquiry Enquiries           | ❌        |
| Retirement Enquiries              | ❌        |
| Withdraw savings Enquiries        | ❌        |
| Withdraw deposit Enquiries        | ❌        |
| Unjoin membership Enquiries       | ❌        |
| Benefit from disasters Enquiries   | ❌        |

### **LOAN MANAGEMENT**

| Loan Type                       | Datatable |
|----------------------------------|-----------|
| Processed Loan                  | ❌        |
| Rejected Loan                   | ❌        |
| Payment Loan                    | ❌        |
| Approved Loan                   | ❌        |

**Others:**
- _Incomplete Pages_
  - Rejected Loan 
  - Payment Loan 
  - Approved Loan 

### **PAYMENT MANAGEMENT**

| Payment Type                    | Datatable |
|----------------------------------|-----------|
| Refund                          | ❌        |
| Retirement                      | ❌        |
| Withdraw Savings                | ❌        |
| Benefit from Disasters          | ❌        |
| Deduction Adjustment            | ❌        |
| Share                           | ❌        |
| Withdraw Deposit                | ❌        |

### **MEMBER MANAGEMENT**

| Member Status                   | Datatable |
|----------------------------------|-----------|
| New Member                      | ❌        |
| Existing Member                 | ❌        |
| Unjoin Member                   | ❌        |
| Retired Member                  | ❌        |

### **ACCESS MANAGEMENT & ALERT MESSAGES**

| Access Type                    | Datatable | Alert                          |
|----------------------------------|-----------|--------------------------------|
| Roles datatable                 | ✔️        | Add, Delete, and Update Role❌   |
| Permissions Datatable           | ✔️        | Create Permission❌               |
| Users Datatable                 | ✔️        | Create User❌                     |

### **BRANCH MANAGEMENT & ALERT MESSAGE**

| Branch Function                | Datatable | Alert                          |
|----------------------------------|-----------|--------------------------------|
| List Branches                  | ❌        | Create, Edit & Delete Branch   |
| Departments                     | ❌        | Create, Edit & Delete Department|
| Representatives                 | ❌        | Add New Representative          |

## RUNTIME EXCEPTIONS

| Exception Type                  |
|----------------------------------|
| Assignment of Share Enquiry     |
| Creating a New Role             |
| Editing a Branch                |
| Editing a Department            |
| Adding User (no Alert)           |

## DUPLICATION OF UNIQUE RECORDS

| Record Type                     |
|----------------------------------|
| Adding a New Branch             |

## UNSTYLED PAGES

| Page Type                       |
|----------------------------------|
| Add, Delete, View Representatives|

## PENDING TASK

| Task Type                       |
|----------------------------------|
| Customization of Login Screen    |
| Session Management in web routing(Page Accessibility by Role )    |
| Display more information in the View Detail modal for Loans and other models where necessary|
| Two Factor aunthentication login OTP|
| Each Enquiry Registration must show created_by |
| Each register when Login, on MyEnquiry menu should show Enquiry he registered and their status  it includes  Loans  and all Enquiries types also simple analtics |

# RECOMMENDED USER REQUIREMENTS

| Requirement                                                           |
|-----------------------------------------------------------------------|
| Remove Action Buttons for users who are not involved in their use     |
| Overdue Notifications for Pending, Rejected, Assigned, Processed items|
| Login Notifications                                                    |
| Customization of Notification Dialogs                                  |
| Warning Alerts for Each Action Performed by the User                  |
| Token for Users Who Approve Loans or Make Payments                    |
| OTP Messages Sent to Customers Must Include Tracking IDs               |
| OTP Messages for All Employees Assigned Tasks                          |
| Search Functionality on Tables by Date, Range, Account Number, or Names|
| Customization of Table Columns for CSV Exports                        |
| The Dashboard Must Include Simple Analysis for Pending, Assigned, Paid, etc.|
| OTP Token for Payment Approval                                         |
| Warning Alerts If Documents Are Missing in Modals                     |
| General Manager Should Prevent the Accountant from Making Payments If the Loan Is Rejected|
| Installments for Cash Loans                                           |
| In Trends, Remove "Sum" and Use "Amount"                             |
| In Trends, Show Pending and Paid Status for Each Loan                |
| In Regions, Change "Arusha Rula District" to "Arumeru"               |

## OTHER RECOMMENDATIONS FROM GM

| Recommendation                                                       |
|-----------------------------------------------------------------------|
| Provide education on how to Register in the ESS System                |
| Provide education on how to Apply for Loans                           |
| Provide education on Loan Products                                    |
| Review Existing Loan Options and Make Comparisons                    |
| Educate on the Importance or Benefits of Borrowing from URA SACCOS Compared to Other Institutions |
| Educate on the Benefits of ESS Membership for SACCOS Members         |
| Establish a Desk at Police Headquarters to Assist with Email Access and Corrections |
