@extends('doctor.layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="row">
            <div class="col-sm-12 d-flex justify-content-end">
                <!-- Note Button -->
                <button type="button" class="btn btn-sm btn-info ml-2 btn-danger" data-toggle="modal" data-target="#noteModal">
                    Important Note
                </button>
            </div>
            
        </div>
            <br>         

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-6">
                <a href="{{ url('doctor/mydoctor') }}">
                    <div class="dash-widget">
                        <span class="dash-widget-bg1"><i class="fa fa-stethoscope" aria-hidden="true"></i></span>
                        <div class="dash-widget-info text-right">
                            <h3>{{ getTotalDoctorsBySession() }}</h3>
                            <span class="widget-title1">Doctors <i class="fa fa-check" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-6">
                <a href="{{ url('doctor/myhospital') }}">
                    <div class="dash-widget">
                        <span class="dash-widget-bg2"><i class="fa fa-hospital-o"></i></span>
                        <div class="dash-widget-info text-right">
                            <h3>{{ getTotalHospitalsBySession() }}</h3>
                            <span class="widget-title2">Hospital <i class="fa fa-check" aria-hidden="true"></i></span>
                        </div>
                    </div>
                 </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Welcome {{ Auth::user()->name }}!</h4>
                            <p>
                            🔔 प्रिय {{ Auth::user()->name }}, कृपया बताएं कि आपको अपने डैशबोर्ड पर कौन-कौन-सी सुविधाएँ चाहिए?  
                            आपकी आवश्यकताओं के आधार पर हम आपके लिए डैशबोर्ड को और बेहतर बनाएँगे।
                            <br><br>
                            👉 कृपया अपनी आवश्यकताएँ हमें <b>mail</b> पर भेजें: <a href="mailto:rogisewa25@gmail.com">rogisewa25@gmail.com</a>

                            </p>

                            <p>
                            🩺 ऑनलाइन पर्चा / अपॉइंटमेंट मैनेजमेंट <br>
                            👨‍⚕️ पेशेंट डेटा मैनेजमेंट <br>
                            💊 मेडिसिन डेटा मैनेजमेंट <br>
                            💳 पेमेंट मैनेजमेंट <br>
                            📄 पर्चा इनवॉइस जनरेशन
                            </p>
          
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Welcome {{ Auth::user()->name }}!</h4>
                        
                             <p>
                            🔔 Dear {{ Auth::user()->name }}, please let us know which features you need on your dashboard.  
                            Based on your requirements, we will improve and customize your dashboard.

                            <br><br>
                            👉 Please send us your requirements by email at: <a href="mailto:rogisewa25@gmail.com">rogisewa25@gmail.com</a>
                             </p>

                            <p>
                            🩺 Online Prescription / Appointment Management <br>
                            👨‍⚕️ Patient Data Management <br>
                            💊 Medicine Data Management <br>
                            💳 Payment Management <br>
                            📄 Prescription Invoice Generation
                            </p>
                        
                    </div>
                </div>
            </div>
        </div>
       
   
    </div>

    <div class="notification-box">
        <div class="msg-sidebar notifications msg-noti">
            <div class="topnav-dropdown-header">
                <span>Messages</span>
            </div>
            <div class="drop-scroll msg-list-scroll" id="msg_list">
                <ul class="list-box">
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">R</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Richard Miles </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item new-message">
                                <div class="list-left">
                                    <span class="avatar">J</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">John Doe</span>
                                    <span class="message-time">1 Aug</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">T</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Tarah Shropshire </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">M</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Mike Litorus</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">C</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Catherine Manseau </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">D</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Domenic Houston </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">B</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Buster Wigton </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">R</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Rolland Webber </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">C</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Claire Mapes </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">M</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Melita Faucher</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">J</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Jeffery Lalor</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">L</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Loren Gatlin</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">T</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Tarah Shropshire</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="topnav-dropdown-footer">
                <a href="chat.html">See all messages</a>
            </div>
        </div>
    </div>
</div>



        <!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="noteModalLabel">Important Note</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <p><strong>✅ English:</strong><br>
            If you want to get a doctor’s or hospital’s profile approved, edit it and copy the URL, then send it via email. - (rogisewa25@gmail.com)
        </p>
        <p><strong>✅ Hindi:</strong><br>
            अगर आप डॉक्टर या अस्पताल का प्रोफ़ाइल अप्रूव कराना चाहते हैं, तो उसे एडिट कीजिए और यूआरएल कॉपी करके मेल कर दीजिए। - (rogisewa25@gmail.com)
        </p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
    </div>
</div>
</div>

@endsection
