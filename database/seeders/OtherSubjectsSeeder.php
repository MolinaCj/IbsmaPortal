<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OtherSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
                                                                                            //BSIT SECTION
            // 1st Year, 1st Semester
            ['code' => 'GE1', 'name' => 'Understanding the Self', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'GE2', 'name' => 'Reading in Phil. History', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'GE3', 'name' => 'The Contemporary World', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'GE4', 'name' => 'Mathematics in the Modern World', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'CC101', 'name' => 'Introduction to Computing', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 1, 'major'=> 1,],
            ['code' => 'CC102', 'name' => 'Computer Programming 1', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 1, 'major'=> 1,],
            ['code' => 'PE1', 'name' => 'Fundamentals in Martial Arts', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 1, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'NSTP1', 'name' => 'National Service Training Program (CWTS1)', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 1, 'major'=> 0,],

            // 1st Year, 2nd Semester
            ['code' => 'GE5', 'name' => 'Purposive Communication', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'GE6', 'name' => 'Art Appreciation', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'GE7', 'name' => 'Science, Technology & Society', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'MS101', 'name' => 'Discrete Mathematics', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 1 ,'prerequisite_code' => 'GE4', 'major'=> 0,],
            ['code' => 'HCI102', 'name' => 'Introduction to Human Computer Interaction', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 1, 'prerequisite_code' => 'CC101', 'major'=> 1,],
            ['code' => 'CC103', 'name' => 'Computer Programming 2', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 1, 'prerequisite_code' => 'CC102', 'major'=> 1,],
            ['code' => 'PE2', 'name' => 'Badminton', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 1, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'NSTP2', 'name' => 'National Service Training Program (CWTS2)', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 1,'prerequisite_code' => 'NSTP1', 'major'=> 0,],

            // 2nd Year, 1st Semester
            ['code' => 'CC104', 'name' => 'Data Structures and Algorithms', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 1,'prerequisite_code' => 'CC103', 'major'=> 1,],
            ['code' => 'PF101', 'name' => 'Object-Oriented Programming', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 1,'prerequisite_code' => 'CC103', 'major'=> 1,],
            ['code' => 'PT101', 'name' => 'Platform Technologies', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 1 ,'prerequisite_code' => 'CC103', 'major'=> 1,], //,'HCI102'
            ['code' => 'FIL1', 'name' => 'Kasanayan sa Maikling Pagpapahayag', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'PE3', 'name' => 'First Aid & Water Safety', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 2, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'Philo', 'name' => 'Philosophy of Human', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'Consti', 'name' => 'Politics & Governance w/ Phil. Constitution', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 1, 'major'=> 0,],

            // 2nd Year, 2st Semester
            ['code' => 'CC105', 'name' => 'Information Management', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 1,'prerequisite_code' => 'CC104', 'major'=> 1,],
            ['code' => 'MS102', 'name' => 'Quantitative Methods', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 1,'prerequisite_code' => 'MS101', 'major'=> 1,],
            ['code' => 'NET101', 'name' => 'Networking 1', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 1,'prerequisite_code' => 'PT101', 'major'=> 1,],
            ['code' => 'IPT101', 'name' => 'Integrative Programming and Technologies 1', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 1, 'major'=> 1,],
            ['code' => 'FIL2', 'name' => 'Panimulang Lingwistika', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'PE4', 'name' => 'Volleyball', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 2, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'ECO', 'name' => 'Economics with LRT', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 1, 'major'=> 0,],

            // 3rd Year, 1st Semester
            ['code' => 'GE9', 'name' => "Rizal's Life, Works and Writing", 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'IM101', 'name' => 'Fundamentals of Database Systems', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 1,'prerequisite_code' => 'CC105', 'major'=> 1,],
            ['code' => 'NET102', 'name' => 'Networking 2', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 1,'prerequisite_code' => 'NET101', 'major'=> 1,],
            ['code' => 'SIA101', 'name' => 'Systems Integration and Architecture', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 1,'prerequisite_code' => 'IPT101', 'major'=> 1,],
            ['code' => 'PF102', 'name' => 'Event Driven Programming', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 1,'prerequisite_code' => 'CC104', 'major'=> 1,],
            ['code' => 'SocSci', 'name' => 'Society & Culture w/ Family Planning', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'IT', 'name' => 'Advanced Computer System Servicing', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 1,'prerequisite_code' => 'CC105', 'major'=> 1,],

            // 3rd Year, 2nd Semester
            ['code' => 'GE8', 'name' => 'Professional Ethics', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'IAS101', 'name' => 'Information Assurance & Security 1', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 1 ,'prerequisite_code' => 'SIA101', 'major'=> 1,],
            ['code' => 'SP101', 'name' => 'Social & Professional Issues', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 1,'prerequisite_code' => 'CC101', 'major'=> 0,],
            ['code' => 'CC106', 'name' => 'Application Development & Emerging Technologies', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 1,'prerequisite_code' => 'CC105', 'major'=> 1,],
            ['code' => 'LIT', 'name' => 'Philippine Literature', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 1, 'major'=> 0,],
            ['code' => 'CAP101', 'name' => 'Capstone Project 1', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 1, 'major'=> 1,], //,'prerequisite_code' => 'IAS101'
            ['code' => 'GE10', 'name' => 'Technical Writing', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 1, 'major'=> 0,],


            // 4th Year, 1st Semester
            ['code' => 'PRAC101', 'name' => 'Practicum', 'description' => '', 'units' => 6, 'semester' => 1, 'year' => 4, 'department_id' => 1,'prerequisite_code' => 'IAS101', 'major'=> 1,], //CC106

            // 4th Year, 2nd Semester
            ['code' => 'IAS102', 'name' => 'Information Assurance & Security 2', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 1 ,'prerequisite_code' => 'IAS101', 'major'=> 1,],
            ['code' => 'CAP102', 'name' => 'Capstone Project 2', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 1,'prerequisite_code' => 'CAP101', 'major'=> 1,],
            ['code' => 'SA101', 'name' => 'System Administration and Maintenance', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 1, 'major'=> 1,],
            ['code' => 'SIA102', 'name' => 'System Integration & Architecture 2', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 1,'prerequisite_code' => 'SIA101', 'major'=> 1,],
            ['code' => 'TECHNO', 'name' => 'Technopreneurship', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 1, 'major'=> 1,],


                                                                                //BSA SECTION
            // 1st Year, 1st Semester
            ['code' => 'GE1', 'name' => 'Understanding the Self', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'GE3', 'name' => 'The Contemporary World', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE13', 'name' => 'Financial Accounting and Reporting', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE11', 'name' => 'Managerial Economics', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'CBME1', 'name' => 'Operation Management and TQM', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE22', 'name' => 'Cost Accounting and Control', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'NSTP1', 'name' => 'National Service Training Program (CWTS1)', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'PathFIT1', 'name' => 'Fundamental in Martial Arts', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'BSA1', 'name' => 'Introduction to Accounting 1', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 2, 'major'=> 0,],

            // 1st Year, 2nd Semester
            ['code' => 'GE6', 'name' => 'Art Appreciation', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'GE5', 'name' => 'Purposive Communication', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE1', 'name' => 'Law on Obligation & Contracts', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE14', 'name' => 'Conceptual Framework & Accounting Standards', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE4', 'name' => 'Management Science', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE15', 'name' => 'Intermediate Accounting 1', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'NSTP2', 'name' => 'National Service Training Program (CWTS2)', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'PathFIT2', 'name' => 'Badminton', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 1, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'BSA2', 'name' => 'Introduction to Accounting 2', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 2,'prerequisite_code' => 'BSA1', 'major'=> 0,],
            
            // 2nd Year - 1st Semester
            ['code' => 'GE4', 'name' => 'Mathematics in the Modern World', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'GE7', 'name' => 'Science, Technology and Society', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE26', 'name' => 'Income Taxation', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE16', 'name' => 'Intermediate Accounting 2', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 2 ,'prerequisite_code' => 'AE15', 'major'=> 0,],
            ['code' => 'AE2', 'name' => 'Business Laws and Regulation', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 2,'prerequisite_code' => 'AE15', 'major'=> 0,],
            ['code' => 'AE19', 'name' => 'Financial Management', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 2],
            ['code' => 'AE21', 'name' => 'IT Application Tools and Business', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'PathFIT3', 'name' => 'Basic Swimming', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 2, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'BSA3', 'name' => 'Advanced Business Math', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 2, 'major'=> 0,],
            
            // 2nd Year - 2nd Semester
            ['code' => 'GE2', 'name' => 'Reading in Philippine History', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE25', 'name' => 'Business Taxation', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE3', 'name' => 'Regulatory Framework & Legal Issues in Business', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 2,'prerequisite_code' => 'AE2', 'major'=> 0,],
            ['code' => 'AE17', 'name' => 'Intermediate Accounting 3', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 2,'prerequisite_code' => 'AE16', 'major'=> 0,],
            ['code' => 'AE10', 'name' => 'Governance, Business Ethics, Risk Management & Internal Control', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 2, 'prerequisite_code' => 'AE2', 'major'=> 0,],
            ['code' => 'AE18', 'name' => 'Financial Markets', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE20', 'name' => 'Accounting Information System', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 2,'prerequisite_code' => 'AE21', 'major'=> 0,],
            ['code' => 'PathFIT4', 'name' => 'Volleyball', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 2, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'BSA4', 'name' => 'Concepts on Applied Economics', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 2, 'major'=> 0,],

            // 3rd Year - 1st Semester
            ['code' => 'GE9', 'name' => 'Rizal’s Life and Works', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'GE11', 'name' => 'Ethics', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE9', 'name' => 'Statistical Analysis with Software Application', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'PrE1', 'name' => 'Auditing & Assurance Principle', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'PrE2', 'name' => 'Auditing & Assurance: Concepts & Application1', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'PrE6', 'name' => 'Accounting For Special Transactions', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 2 ,'prerequisite_code' => 'AE13', 'major'=> 0,],
            ['code' => 'PrE7', 'name' => 'Accounting For Business Combination', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 2, 'prerequisite_code' => 'AE13', 'major'=> 0,],
            ['code' => 'Elec1', 'name' => 'Updates in Financial Reporting Standards', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 2, 'major'=> 0,],

            // 3rd Year - 2nd Semester
            ['code' => 'AE6', 'name' => 'Accounting Research Methods', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE5', 'name' => 'International Business & Trade', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'PrE8', 'name' => 'Accounting for Government & Non-Profit Organization', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'PrE3', 'name' => 'Auditing & Assurance: Concepts & Application 2', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 2,'prerequisite_code' => 'PrE2', 'major'=> 0,],
            ['code' => 'PrE4', 'name' => 'Auditing & Assurance: Specialized Industries', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 2,'prerequisite_code' => 'PrE6', 'major'=> 0,], //PrE7
            ['code' => 'PrE5', 'name' => 'Auditing in a CIS Environment', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 2,'prerequisite_code' => 'AE20', 'major'=> 0,],
            ['code' => 'Elec2', 'name' => 'Human Behavior in Organization', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'BSA5', 'name' => 'Concepts on Business Marketing', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 2, 'major'=> 0,],

            //Summer
            ['code' => 'CBME2', 'name' => 'Strategic Management', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE7', 'name' => 'Economic Development', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'GE10', 'name' => 'GE Elective', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 3, 'department_id' => 2, 'major'=> 0,],
            
            // 4th Year - 1st Semester
            ['code' => 'AE7', 'name' => 'Accounting Internship', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'AE8', 'name' => 'Accountancy Research', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 2,'prerequisite_code' => 'AE6', 'major'=> 0,],
            
            // 4th Year - 2nd Semester
            ['code' => 'GE12', 'name' => 'Social Science and Philosophy', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'CBME2', 'name' => 'Strategic Management', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 2,'prerequisite_code' => 'CBME1', 'major'=> 0,],
            ['code' => 'AE24', 'name' => 'Strategic Business Analysis', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'Elec3', 'name' => 'Operation Auditing', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 2, 'major'=> 0,],
            ['code' => 'Elec4', 'name' => 'Valuation Concepts & Methods', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 2, 'major'=> 0,],



                                                                        // BSBA SETION
            // 1st Year, 1st Semester
            ['code' => 'GE1', 'name' => 'Understanding The Self', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'GE2', 'name' => 'Reading in Philippine History', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'GE3', 'name' => 'The Contemporary World', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'GE4', 'name' => 'Mathematics in the Modern World', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'PE1', 'name' => 'Fundamentals in Martial Arts', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Fil1', 'name' => 'Kasanayan sa Maikling Pagpapahayag', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'NSTP1', 'name' => 'National Service Training Program', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'BA1', 'name' => 'Advanced Business Mathematics', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'BA2', 'name' => 'College Accounting 1', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 3, 'major'=> 0,],

            // 1st Year, 2nd Semester
            ['code' => 'GE5', 'name' => 'Purposive Communication', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'GE6', 'name' => 'Art Appreciation', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'GE7', 'name' => 'Science, Technology, and Society', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'GE8', 'name' => 'Ethics', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'PE2', 'name' => 'Badminton', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Fil2', 'name' => 'Panimulang Lingwistika', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'NSTP2', 'name' => 'National Service Training Program', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'BA3', 'name' => 'College Accounting 2', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 3,'prerequisite_code' => 'BA2', 'major'=> 0,],
        
            // 2nd Year, 1st Semester
            ['code' => 'GE9', 'name' => 'Rizal’s Life and Works', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'E_Effective', 'name' => 'Mathematics, Science, & Technology', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Core1', 'name' => 'Basic Microeconomics (Eco)', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Prof1', 'name' => 'Financial Management', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'PE3', 'name' => 'First Aid and Water Safety', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'BA4', 'name' => 'Advanced Applied Economics', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'GE_Elec2', 'name' => 'Social Science & Philosophy', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
        
            // 2nd Year, 2nd Semester
            ['code' => 'GE_Effective', 'name' => 'Arts and Humanities', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'BA_Core1', 'name' => 'Business Law (Obligations and Contracts)', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'BA_Core2', 'name' => 'Taxation (Income Taxation)', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Prof2', 'name' => 'Financial Analysis and Reporting', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 3,'prerequisite_code' => 'Prof1', 'major'=> 0,],
            ['code' => 'Prof3', 'name' => 'Banking and Financial Institutions', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'PE4', 'name' => 'Volleyball', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'BA5', 'name' => 'Advanced Business Marketing', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 3, 'major'=> 0,],
        
            // 3rd Year, 1st Semester
            ['code' => 'CBMEC1', 'name' => 'Strategic Management', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Core2', 'name' => 'Good Governance and Social Responsibility', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 3,'prerequisite_code' => 'GE8', 'major'=> 0,],
            ['code' => 'Core3', 'name' => 'Human Resource Management', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Core4', 'name' => 'International Trade and Agreement', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Prof4', 'name' => 'Monetary Policy and Central Banking', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 3,'prerequisite_code' => 'Prof3', 'major'=> 0,],
            ['code' => 'Prof5', 'name' => 'Investment and Portfolio Management', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 3, 'major'=> 0,],
        
            // 3rd Year, 2nd Semester
            ['code' => 'BA_Core3', 'name' => 'Business Research', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'CBMEC2', 'name' => 'Operation Management (TQM)', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Prof6', 'name' => 'Credit and Collection', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 3,'prerequisite_code' => 'Prof3', 'major'=> 0,],
            ['code' => 'Prof7', 'name' => 'Capital Market', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 3,'prerequisite_code' => 'Prof4', 'major'=> 0,],
            ['code' => 'Lit', 'name' => 'Philippine Literature', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Elective1', 'name' => 'Cooperative Management', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 3, 'major'=> 0,],
        
            // 4th Year, 1st Semester
            ['code' => 'Prof8', 'name' => 'Special Topics in Financial Management', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Elective2', 'name' => 'Entrepreneurial Management', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Elective3', 'name' => 'Franchising', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'Core5', 'name' => 'Thesis or Feasibility Study', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 3, 'major'=> 0,],

            // 4th Year, 2nd Semester
            ['code' => 'Elective4', 'name' => 'Global Finance and Electronic Banking', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 3, 'major'=> 0,],
            ['code' => 'OJT', 'name' => 'Practicum Work Integrated Learning', 'description' => '', 'units' => 6, 'semester' => 2, 'year' => 4, 'department_id' => 3, 'major'=> 0,],
            
                                                                        // Criminology SETION
             // 1st Year, 1st Semester
            ['code' => 'GE1', 'name' => 'Understanding The Self', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 4, 'major'=> 0,],
            ['code' => 'GE2', 'name' => 'The Contemporary World', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 4, 'major'=> 0,],
            ['code' => 'GE3', 'name' => 'Mathematics in Modern World', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 4, 'major'=> 0,],
            ['code' => 'EC1', 'name' => 'People and Earths Ecosystems', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 4, 'major'=> 0,],
            ['code' => 'Crimi1', 'name' => 'Introduction to Criminology', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 4, 'major'=> 0,],
            ['code' => 'PE1', 'name' => 'Fundamentals in Martial Arts', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 1, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Fil1', 'name' => 'Kasanayan sa Maikling Pagpapahayag', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 4,'major'=> 0,],
            ['code' => 'NSTP1', 'name' => 'National Service Training Program', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 4,'major'=> 0,],
            ['code' => 'BSC1', 'name' => 'Advanced Study in Religious Belief System', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 4,'major'=> 0,],

            // 1st Year, 2nd Semester
            ['code' => 'GE5', 'name' => 'Purposive Communication', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 4,'major'=> 0,],
            ['code' => 'GE6', 'name' => 'Science, Technology, and Society', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 4,'major'=> 0,],
            ['code' => 'GE7', 'name' => 'Ethics', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 4,'major'=> 0,],
            ['code' => 'GE8', 'name' => 'Art Appreciation', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 4,'major'=> 0,],
            ['code' => 'CLJ1', 'name' => 'Intro to the Philippine Criminal Justice System', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 4,'major'=> 0,],
            ['code' => 'PE2', 'name' => 'Arnis and Disarming Techniques', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 1, 'department_id' => 4,'major'=> 0,],
            ['code' => 'NSTP2', 'name' => 'National Service Training Program', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 4,'major'=> 0,],
            ['code' => 'BSC2', 'name' => 'Concepts in Social Science 2', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 4,'major'=> 0,],

            // 2nd Year, 1st Semester
            ['code' => 'EC2', 'name' => 'Philippine Indigenous Communities', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'PC', 'name' => 'Life and Works of Rizal', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'CDI1', 'name' => 'Fundamentals of Investigation and Intelligence', 'description' => '', 'units' => 4, 'semester' => 1, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'LEA1', 'name' => 'Law Enforcement Organization & Administration', 'description' => '', 'units' => 4, 'semester' => 1, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'LEA2', 'name' => 'Comparative Models in Policing', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Crimi2', 'name' => 'Theories of Crime Causation', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 4,'prerequisite_code' => 'Crim1','major'=> 0,],
            ['code' => 'PE3', 'name' => 'Fundamentals of Marksmanship', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'BSC3', 'name' => 'Concepts in Applied Social Science 2', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 4,'major'=> 0,],

            // 2nd Year, 2nd Semester
            ['code' => 'CA1', 'name' => 'Institutional Corrections', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Forensic1', 'name' => 'Forensic Photography', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'CFLM1', 'name' => 'Character Formation, Nationalism and Patriotism', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Forensic2', 'name' => 'Personal Identification Techniques', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Crimi3', 'name' => 'Human Behavior and Victimology', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 4,'prerequisite_code' => 'Crim2','major'=> 0,],
            ['code' => 'LEA3', 'name' => 'Introduction to Industrial Security Concepts', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'PE4', 'name' => 'First Aid and Water Safety', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'CDI2', 'name' => 'Specialized Crime Investigation 1 with Legal Medicine', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 2, 'department_id' => 4,'prerequisite_code' => 'CDI1','major'=> 0,],
            ['code' => 'BSC4', 'name' => 'Advanced Critical Thinking in the 21st Century', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 4,'major'=> 0,],

            //Summer 2nd Year
            ['code' => 'GE4', 'name' => 'Readings in Philippine History', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Fil2', 'name' => 'Panimulang Lingwistika', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 2, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Lit1', 'name' => 'Philippine Literature', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 2, 'department_id' => 4,'major'=> 0,],


            // 3rd Year, 1st Semester
            ['code' => 'CLJ2', 'name' => 'Human Rights Education', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 4,'prerequisite_code' => 'CLJ1','major'=> 0,],
            ['code' => 'CDI3', 'name' => 'Specialized Crime Investigation 2', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 4,'prerequisite_code' => 'CDI2','major'=> 0,],
            ['code' => 'Forensic3', 'name' => 'Forensic Chemistry and Toxicology', 'description' => '', 'units' => 5, 'semester' => 1, 'year' => 3, 'department_id' => 4,'major'=> 0,],
            ['code' => 'CLJ3', 'name' => 'Criminal Law (Book 1)', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 4,'major'=> 0,],
            ['code' => 'LEA4', 'name' => 'Law Enforcement Operations and Planning', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 4,'prerequisite_code' => 'LEA1','major'=> 0,],
            ['code' => 'CDI4', 'name' => 'Traffic Management and Accident Investigation with Driving', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 4,'prerequisite_code' => 'CDI1','major'=> 0,],
            ['code' => 'BSC5', 'name' => 'Politics & Governance with Philippine Constitution', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 3, 'department_id' => 4,'major'=> 0,],

            // 3rd Year, 2nd Semester
            ['code' => 'CA2', 'name' => 'Non-Institutional Corrections', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 4,'prerequisite_code' => 'CA1','major'=> 0,],
            ['code' => 'CLJ4', 'name' => 'Criminal Law (Book 2)', 'description' => '', 'units' => 4, 'semester' => 2, 'year' => 3, 'department_id' => 4,'prerequisite_code' => 'CLJ3','major'=> 0,],
            ['code' => 'Forensic4', 'name' => 'Questioned Documents Examinations', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 4,'prerequisite_code' => 'Forensic3','major'=> 0,],
            ['code' => 'Crimi5', 'name' => 'Juvenile Delinquency and Juvenile Justice System', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Forensic5', 'name' => 'Lie Detection Techniques', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 4,'major'=> 0,],
            ['code' => 'CDI6', 'name' => 'Fire Protection and Arson Investigation', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 4,'major'=> 0,],
            ['code' => 'CDI5', 'name' => 'Technical English 1 (Technical Report Writing & Presentation)', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 4,'major'=> 0,],
            ['code' => 'BSC6', 'name' => 'Advanced Community Engagement, Solidarity & Citizenship', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 3, 'department_id' => 4,'major'=> 0,],

            //Summer 3rd Year
            ['code' => 'EC3', 'name' => 'Philippine Popular Culture', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 3, 'department_id' => 4,'major'=> 0,],
            ['code' => 'CFLM2', 'name' => 'Character Formation with Leadership, Decision', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 3, 'department_id' => 4,'prerequisite_code' => 'CFLM1','major'=> 0,],
            ['code' => 'Crim4', 'name' => 'Professional Conduct and Ethical Standards', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 3, 'department_id' => 4,'major'=> 0,],




            // 4th Year, 1st Semester
            ['code' => 'CrimPrac1', 'name' => 'Internship (On The Job Training 1)', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 4,'major'=> 0,],
            ['code' => 'CA3', 'name' => 'Therapeutic Modalities', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 4, 'department_id' => 4,'prerequisite_code' => 'CA1','major'=> 0,],
            ['code' => 'CLJ5', 'name' => 'Evidence', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Crimi6', 'name' => 'Dispute Resolution and Crises Incidents Management', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Crimi7', 'name' => 'Criminology Research 1 (Research Methods with Applied Statistics)', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 4,'major'=> 0,],
            ['code' => 'CDI7', 'name' => 'Vice and Drug Education and Control', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 4,'major'=> 0,],
            ['code' => 'AdGE', 'name' => 'General Chemistry (Organic)', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 4, 'department_id' => 4,'major'=> 0,],

            // 4th Year, 2nd Semester
            ['code' => 'CrimPrac2', 'name' => 'Internship (On The Job Training 2)', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Forensic6', 'name' => 'Forensic Ballistics', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 4,'prerequisite_code' => 'Forensic3','major'=> 0,],
            ['code' => 'CLJ6', 'name' => 'Criminal Procedure and Court Testimony', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 4,'prerequisite_code' => 'CLJ5','major'=> 0,],
            ['code' => 'Crimi8', 'name' => 'Criminal Research 2 (Thesis Writing & Presentation)', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 4,'prerequisite_code' => 'Crim7','major'=> 0,],
            ['code' => 'CDI8', 'name' => 'Technical English 2 (Legal Forms)', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 4,'prerequisite_code' => 'CDI5','major'=> 0,],
            ['code' => 'CDI9', 'name' => 'Introduction to Cybercrime and Environmental', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 4,'major'=> 0,],
            ['code' => 'Audit', 'name' => 'Course Audit', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 4, 'department_id' => 4,'major'=> 0,],

                                                                                            // Midwifery SETION
            // // 1st Year, 1st Semester
            // ['code' => 'GE1', 'name' => 'Understanding The Self', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 5],
            // ['code' => 'GE2', 'name' => 'The Contemporary World', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 5],
            // ['code' => 'GE3', 'name' => 'Mathematics in the Modern World', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 5],
            // ['code' => 'PE1', 'name' => 'Fundamentals in Martial Arts', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 1, 'department_id' => 5],
            // ['code' => 'M100', 'name' => 'Foundation of Midwifery Practice', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 5],
            // ['code' => 'CP100', 'name' => 'Clinical Practicum 100', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 5],
            // ['code' => 'Fil1', 'name' => 'Kasanayan sa Maikling Pagpapahayag', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 5],
            // ['code' => 'NSTP1', 'name' => 'National Service Training Program 1', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 1, 'department_id' => 5],
            // ['code' => 'AP152', 'name' => 'Anatomy and Physiology', 'description' => '', 'units' => 5, 'semester' => 1, 'year' => 1, 'department_id' => 5],

            // // 1st Year, 2nd Semester
            // ['code' => 'GE5', 'name' => 'Purposive Communication', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 5],
            // ['code' => 'GE6', 'name' => 'Art Appreciation', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 5],
            // ['code' => 'GE7', 'name' => 'Science, Technology, & Society', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 5],
            // ['code' => 'GE8', 'name' => 'Ethics', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 5],
            // ['code' => 'M101', 'name' => 'Normal OB, Imm, Care of the Newborn', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 5],
            // ['code' => 'ZO132', 'name' => 'Zoology (lec/lab)', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 5],
            // ['code' => 'NSTP2', 'name' => 'National Service Training Program 2', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 5],
            // ['code' => 'PE2', 'name' => 'Badminton', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 1, 'department_id' => 5],
            // ['code' => 'CP101', 'name' => 'Clinical Practicum', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 1, 'department_id' => 5],


            // //SUMMER CLASSES FOR MIDWIFERY
            // ['code' => 'GE2', 'name' => 'Reading in Philippine History', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 1, 'department_id' => 5],
            // ['code' => 'Fil2', 'name' => 'Panimulang Lingwistika', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 1, 'department_id' => 5],
            // ['code' => 'Lit', 'name' => 'Philippine Literature', 'description' => '', 'units' => 3, 'semester' => 3, 'year' => 1, 'department_id' => 5],
            // ['code' => 'CP1018', 'name' => 'Clinical Practicum 101B', 'description' => '', 'units' => 1, 'semester' => 3, 'year' => 1, 'department_id' => 5],



            // // 2nd Year, 1st Semester
            // ['code' => 'Rizal', 'name' => 'Rizal\'s Life', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 5],
            // ['code' => 'MP241', 'name' => 'Micro & Parasitology (lec/lab)', 'description' => '', 'units' => 4, 'semester' => 1, 'year' => 2, 'department_id' => 5],
            // ['code' => 'M102', 'name' => 'Pathologic OB, Basic Gynecology, FP Tech, Care', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 5],
            // ['code' => 'PHC1', 'name' => 'Primary Health Care', 'description' => '', 'units' => 4, 'semester' => 1, 'year' => 2, 'department_id' => 5],
            // ['code' => 'PE3', 'name' => 'First Aid and Water Safety', 'description' => '', 'units' => 2, 'semester' => 1, 'year' => 2, 'department_id' => 5],
            // ['code' => 'CP_PGC1', 'name' => 'CP PGC1', 'description' => '', 'units' => 3, 'semester' => 1, 'year' => 2, 'department_id' => 5],
            // ['code' => 'CP102A', 'name' => 'Clinical Practicum 102 A', 'description' => '', 'units' => 6, 'semester' => 1, 'year' => 2, 'department_id' => 5],

            // // 2nd Year, 2nd Semester
            // ['code' => 'ND132', 'name' => 'Nutrition & Dietetics', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 5],
            // ['code' => 'ST231', 'name' => 'Principles, Methods, & Strategies of Teaching', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 5],
            // ['code' => 'EC232', 'name' => 'Health Economics W/ LRT', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 5],
            // ['code' => 'M103', 'name' => 'Professional Growth & Development & Bio-Ethics', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 5],
            // ['code' => 'PHC2', 'name' => 'Primary Health Care 2', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 5],
            // ['code' => 'CP_PHC2', 'name' => 'CP PHC2', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 2, 'department_id' => 5],
            // ['code' => 'CP102B', 'name' => 'Clinical Practicum 102 B', 'description' => '', 'units' => 7, 'semester' => 2, 'year' => 2, 'department_id' => 5],
            // ['code' => 'PE4', 'name' => 'Volleyball', 'description' => '', 'units' => 2, 'semester' => 2, 'year' => 2, 'department_id' => 5],
            // ['code' => 'Audit', 'name' => 'Program Audit', 'description' => '', 'units' => 3, 'semester' => 2, 'year' => 2, 'department_id' => 5],

            ];

            // Use updateOrInsert to prevent duplicate errors
        foreach ($subjects as $subject) {
            // Find the prerequisite ID if a prerequisite_code exists
            $prerequisiteId = null;
            if (isset($subject['prerequisite_code'])) {
                $prerequisite = DB::table('subjects')->where('code', $subject['prerequisite_code'])->first();
                $prerequisiteId = $prerequisite ? $prerequisite->id : null;
            }

            // Merge prerequisite_id into the subject data
            $subject['prerequisite_id'] = $prerequisiteId;
            unset($subject['prerequisite_code']); // Remove the prerequisite_code after processing

            DB::table('subjects')->updateOrInsert(
                ['code' => $subject['code'], 'department_id' => $subject['department_id']],
                $subject
            );
        }

    }
}
