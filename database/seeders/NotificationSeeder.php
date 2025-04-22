<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NotificationTemplates;
use App\Models\NotificationTemplateLangs;
class NotificationSeeder extends Seeder
{
    public function run()
    {
        $emailTemplates = [
            'New User',            
            'Send Mail To Agent',
            'Send Mail To Customer',
            'Send Mail To Admin',
            'Reply Mail To Customer',
            'Reply Mail To Agent',
            'Reply Mail To Admin',
            'Ticket Close',
        ];

        $defaultTemplate = [
            'New User' => [
                'subject' => 'Login Detail',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "Email": "email",
                    "Password": "password"
                  }',
                'lang' => [
                    'ar' => '<p>مرحبا ، مرحبا بك في {app_name}.</p>
                            <p>&nbsp;</p>
                            <p>البريد الالكتروني : {email}</p>
                            <p>كلمة السرية : {password}</p>
                            <p>{app_url}</p>
                            <p>&nbsp;</p>
                            <p>شكرا</p>
                            <p>{ app_name }</p>',
                    'da' => '<p>Hej, velkommen til { app_name }.</p>
                            <p>&nbsp;</p>
                            <p>E-mail: { email }-</p>
                            <p>kodeord: { password }</p>
                            <p>{app_url}</p>
                            <p>&nbsp;</p>
                            <p>Tak.</p>
                            <p>{ app_name }</p>',
                    'de' => '<p>Hallo, Willkommen bei {app_name}.</p>
                            <p>&nbsp;</p>
                            <p>E-Mail: {email}</p>
                            <p>Kennwort: {password}</p>
                            <p>{app_url}</p>
                            <p>&nbsp;</p>
                            <p>Danke,</p>
                            <p>{Anwendungsname}</p>',
                    'en' => '<p>Hello,&nbsp;<br>Welcome to {app_name}.</p><p><b>Email </b>: {email}<br><b>Password</b> : {password}</p><p>{app_url}</p><p>Thanks,<br>{app_name}</p>',
                    'es' => '<p>Hola, Bienvenido a {app_name}.</p>
                            <p>&nbsp;</p>
                            <p>Correo electr&oacute;nico: {email}</p>
                            <p>Contrase&ntilde;a: {password}</p>
                            <p>&nbsp;</p>
                            <p>{app_url}</p>
                            <p>&nbsp;</p>
                            <p>Gracias,</p>
                            <p>{app_name}</p>',
                    'fr' => '<p>Bonjour, Bienvenue dans { app_name }.</p>
                            <p>&nbsp;</p>
                            <p>E-mail: { email }</p>
                            <p>Mot de passe: { password }</p>
                            <p>{ adresse_url }</p>
                            <p>&nbsp;</p>
                            <p>Merci,</p>
                            <p>{ nom_app }</p>',
                    'it' => '<p>Ciao, Benvenuti in {app_name}.</p>
                            <p>&nbsp;</p>
                            <p>Email: {email} Password: {password}</p>
                            <p>&nbsp;</p>
                            <p>{app_url}</p>
                            <p>&nbsp;</p>
                            <p>Grazie,</p>
                            <p>{app_name}</p>',
                    'ja' => '<p>こんにちは、 {app_name}へようこそ。</p>
                            <p>&nbsp;</p>
                            <p>E メール : {email}</p>
                            <p>パスワード : {password}</p>
                            <p>{app_url}</p>
                            <p>&nbsp;</p>
                            <p>ありがとう。</p>
                            <p>{app_name}</p>',
                    'nl' => '<p>Hallo, Welkom bij { app_name }.</p>
                                <p>&nbsp;</p>
                                <p>E-mail: { email }</p>
                                <p>Wachtwoord: { password }</p>
                                <p>{ app_url }</p>
                                <p>&nbsp;</p>
                                <p>Bedankt.</p>
                                <p>{ app_name }</p>',
                    'pl' => '<p>Witaj, Witamy w aplikacji {app_name }.</p>
                            <p>&nbsp;</p>
                            <p>E-mail: {email }</p>
                            <p>Hasło: {password }</p>
                            <p>{app_url }</p>
                            <p>&nbsp;</p>
                            <p>Dziękuję,</p>
                            <p>{app_name }</p>',
                    'ru' => '<p>Здравствуйте, Добро пожаловать в { app_name }.</p>
                            <p>&nbsp;</p>
                            <p>Адрес электронной почты: { email }</p>
                            <p>Пароль: { password }</p>
                            <p>&nbsp;</p>
                            <p>{ app_url }</p>
                            <p>&nbsp;</p>
                            <p>Спасибо.</p>
                            <p>{ имя_программы }</p>',
                    'pt' => '<p>Ol&aacute;, Bem-vindo a {app_name}.</p>
                            <p>&nbsp;</p>
                            <p>E-mail: {email}</p>
                            <p>Senha: {senha}</p>
                            <p>{app_url}</p>
                            <p>&nbsp;</p>
                            <p>Obrigado,</p>
                            <p>{app_name}</p>
                            <p>{ имя_программы }</p>',
                    'tr' => '<p>Ol, { app_name } olanağına hoş geldiniz.</p>
                            <p>&nbsp;</p>
                            <p>E-posta: {email}</p>
                            <p>Parola: {password}</p>
                            <p>{app_url}</p>
                            <p>&nbsp;</p>
                            <p>Teşekkür ederim.</p>
                            <p>{app_name}</p>
                            <p>{ program_adı }</p>',
                    'he' => '<p>שלום, &nbsp;<br>ברוכים הבאים אל {app_name}.</p><p><b>דואל </b>: {הדוא " ל}<br><b>סיסמה</b> : {password}</p><p>{app_url}</p><p>תודה,<br>{app_name}</p>',
                    'zh' => '<p>您好，<br>欢迎访问 {app_name}。</p><p><b>电子邮件 </b>: {email}<br><b>密码</b> : {password}</p><p>{app_url}</p><p>谢谢，<br>{app_name}</p>',
                    'pt-br' => '<p>Ol&aacute;, Bem-vindo a {app_name}.</p>
                                <p>&nbsp;</p>
                                <p>E-mail: {email}</p>
                                <p>Senha: {senha}</p>
                                <p>{app_url}</p>
                                <p>&nbsp;</p>
                                <p>Obrigado,</p>
                                <p>{app_name}</p>
                                <p>{ имя_программы }</p>',

                ],
            ],
            'Send Mail To Agent' => [
                'subject' => 'Ticket Detail',
                'variables' => '{
                    "App Name": "app_name",
                    "Ticket Name": "ticket_name",
                    "Ticket Id": "ticket_id",
                    "App Url": "app_url",
                    "Email": "email",
                    "Ticket URL": "ticket_url"
                  }',
                  'lang' => [
                        'ar' => '<p>مرحبًا,&nbsp;<br>مرحبا بكم في {app_name}.</p>
                                 <p><strong>لقد تم تخصيص تذكرة جديدة لك : </strong> وهنا تفاصيل التذكرة.</p>
                                <p><strong>اسم التذكرة</strong> : {ticket_name} </p>
                                <p><strong>رقم التذكرة</strong> : {ticket_id}</p>
                                <p><strong>بريد إلكتروني</strong> : {email}<br></p>
                                <p><strong>عنوان URL للتطبيق</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>شكرًا,<br>{app_name}</p>',
    
                        'da' => '<p>Hej,&nbsp;<br>Velkommen til {app_name}.</p>
                                <p><strong>Ny billet er blevet tildelt dig: </strong> Her er billetoplysningerne.</p>
                                <p><strong>Billetnavn</strong> : {ticket_name} </p>
                                <p><strong>Billetnummer</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>App URL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Tak,<br>{app_name}</p>',
    
                        'de' => '<p>Hallo,&nbsp;<br>Willkommen bei {app_name}.</p>
                                <p><strong>Ihnen wurde ein neues Ticket zugewiesen: </strong> Hier finden Sie die Ticketdetails.</p>
                                <p><strong>Ticketname</strong> : {ticket_name} </p>
                                <p><strong>Losnummer</strong> : {ticket_id}</p>
                                <p><strong>E-Mail</strong> : {email}<br></p>
                                <p><strong>App-URL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Danke,<br>{app_name}</p>',
    
                        'en' => '<p>Hello,&nbsp;<br>Welcome to {app_name}.</p>
                                <p><strong>New Ticket Has Been Assigned To You:</strong> Here are the ticket details.</p>
                                <p><strong>Ticket Name</strong>: {ticket_name} </p>
                                <p><strong>Ticket Number</strong>: {ticket_id}</p>
                                <p><strong>Email</strong>: {email}<br></p>
                                <p><strong>App URL</strong>: {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Thanks,<br>{app_name}</p>',

    
                        'es' => '<p>Hola,&nbsp;<br>Bienvenido a {app_name}.</p> 
                                <p><strong>Se le ha asignado un nuevo billete: </strong> Aquí están los detalles del billete.</p>
                                <p><strong>Nombre del billete</strong> : {ticket_name} </p>
                                <p><strong>Número de billete</strong> : {ticket_id}</p>
                                <p><strong>Correo electrónico</strong> : {email}<br></p>
                                <p><strong>URL de la aplicación</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Gracias,<br>{app_name}</p>',
    
                        'fr' => '<p>Bonjour,&nbsp;<br>Bienvenue à {app_name}.</p>
                                <p><strong>Un nouveau ticket vous a été attribué : </strong> Voici les détails du billet.</p>
                                <p><strong>Nom du billet</strong> : {ticket_name} </p>
                                <p><strong>Numéro de billet</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>URL de lapplication</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Merci,<br>{app_name}</p>',
    
                        'it' => '<p>Ciao,&nbsp;<br>Benvenuto a {app_name}.</p>
                                <p><strong>Ti è stato assegnato un nuovo biglietto:</strong> Ecco i dettagli del biglietto.</p>
                                <p><strong>Nome del biglietto</strong> : {ticket_name} </p>
                                <p><strong>Numero del biglietto</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>URL dellapp</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Grazie,<br>{app_name}</p>',
    
                        'ja' => '<p>こんにちは,&nbsp;<br>へようこそ {app_name}.</p>
                                <p><strong>新しいチケットがあなたに割り当てられました: </strong> チケット詳細はこちらです。</p>
                                <p><strong>チケット名</strong> : {ticket_name} </p>
                                <p><strong>チケット番号</strong> : {ticket_id}</p>
                                <p><strong>電子メール</strong> : {email}<br></p>
                                <p><strong>アプリのURL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>ありがとう,<br>{app_name}</p>',
    
                        'nl' => '<p>Hallo,&nbsp;<br>Welkom bij {app_name}.</p>
                                <p><strong>Er is een nieuw ticket aan u toegewezen: </strong> Hier zijn de ticketgegevens.</p>
                                <p><strong>Ticketnaam</strong> : {ticket_name} </p>
                                <p><strong>Ticketnummer</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>App-URL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Bedankt,<br>{app_name}</p>',
    
                        'pl' => '<p>Cześć,&nbsp;<br>Witamy w {app_name}.</p>
                                <p><strong>Nowy bilet został Ci przypisany: </strong> Oto szczegóły biletów.</p>
                                <p><strong>Nazwa biletu</strong> : {ticket_name} </p>
                                <p><strong>Numer biletu</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>Adres URL aplikacji</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Dzięki,<br>{app_name}</p>',
    
                        'ru' => '<p>Привет,&nbsp;<br>Добро пожаловать в {app_name}.</p>
                                <p><strong>Вам назначен новый билет: </strong> Вот подробности билета.</p>
                                <p><strong>Название билета</strong> : {ticket_name} </p>
                                <p><strong>Номер билета</strong> : {ticket_id}</p>
                                <p><strong>Электронная почта</strong> : {email}<br></p>
                                <p><strong>URL-адрес приложения</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Спасибо,<br>{app_name}</p>',
    
                        'pt' => '<p>Olá,&nbsp;<br>Bem-vindo ao {app_name}.</p>
                                <p><strong>Um novo ticket foi atribuído a você: </strong> Aqui estão os detalhes do ingresso.</p>
                                <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                                <p><strong>Número do bilhete</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>URL do aplicativo</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Obrigado,<br>{app_name}</p>',
    
                        'tr' => '<p>Merhaba,&nbsp;<br>Hoş geldiniz {app_name}.</p>
                                 <p><strong>Yeni Bilet Size Tahsis Edildi : </strong> İşte bilet detayları.</p>
                                <p><strong>Bilet Adı</strong> : {ticket_name} </p>
                                <p><strong>Bilet Numarası</strong> : {ticket_id}</p>
                                <p><strong>E-posta</strong> : {email}<br></p>
                                <p><strong>Uygulama URLsi</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Teşekkürler,<br>{app_name}</p>',
    
                        'zh' => '<p>你好,&nbsp;<br>欢迎来到 {app_name}.</p>
                               <p><strong>新票已分配给您： </strong> 这是门票详细信息。</p>
                                <p><strong>票名</strong> : {ticket_name} </p>
                                <p><strong>票号</strong> : {ticket_id}</p>
                                <p><strong>电子邮件</strong> : {email}<br></p>
                                <p><strong>应用程序网址</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>谢谢,<br>{app_name}</p>',
    
                        'he' => '<p>שלום,&nbsp;<br>ברוכים הבאים ל {app_name}.</p>
                               <p><strong>כרטיס חדש הוקצה לך: </strong> להלן פרטי הכרטיסים.</p>
                                <p><strong>שם הכרטיס</strong> : {ticket_name} </p>
                                <p><strong>מספר כרטיס</strong> : {ticket_id}</p>
                                <p><strong>אֶלֶקטרוֹנִי</strong> : {email}<br></p>
                                <p><strong>כתובת האתר של האפליקציה</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>תוֹדָה,<br>{app_name}</p>',
    
                        'pt-br' => '<p>Olá,&nbsp;<br>Bem-vindo ao {app_name}.</p>
                                <p><strong>Um novo ticket foi atribuído a você: </strong> Aqui estão os detalhes do ingresso.</p>
                                <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                                <p><strong>Número do bilhete</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>URL do aplicativo</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Obrigado,<br>{app_name}</p>',
                    ],
            ],
            'Send Mail To Customer' => [
                'subject' => 'Ticket Detail',
                'variables' => '{
                    "App Name": "app_name",
                    "Ticket Name": "ticket_name",
                    "Ticket Id": "ticket_id",
                    "App Url": "app_url",
                    "Email": "email",
                    "Ticket URL": "ticket_url"
                  }',
                  'lang' => [
                        'ar' => '<p>مرحبًا,&nbsp;<br>مرحبا بكم في {app_name}.</p>
                                <p><strong>اسم التذكرة</strong> : {ticket_name} </p>
                                <p><strong>رقم التذكرة</strong> : {ticket_id}</p>
                                <p><strong>بريد إلكتروني</strong> : {email}<br></p>
                                <p><strong>عنوان URL للتطبيق</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>شكرًا,<br>{app_name}</p>',
    
                        'da' => '<p>Hej,&nbsp;<br>Velkommen til {app_name}.</p>
                                <p><strong>Billetnavn</strong> : {ticket_name} </p>
                                <p><strong>Billetnummer</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>App URL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Tak,<br>{app_name}</p>',
    
                        'de' => '<p>Hallo,&nbsp;<br>Willkommen bei {app_name}.</p>
                                <p><strong>Ticketname</strong> : {ticket_name} </p>
                                <p><strong>Losnummer</strong> : {ticket_id}</p>
                                <p><strong>E-Mail</strong> : {email}<br></p>
                                <p><strong>App-URL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Danke,<br>{app_name}</p>',
    
                        'en' => '<p>Hello,&nbsp;<br>Welcome to {app_name}.</p>
                                <p><strong>Ticket Name</strong> : {ticket_name} </p>
                                <p><strong>Ticket Number</strong> : {ticket_id}</p>
                                <p><strong>Email</strong> : {email}<br></p>
                                <p><strong>App URL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Thanks,<br>{app_name}</p>',
    
                        'es' => '<p>Hola,&nbsp;<br>Bienvenido a {app_name}.</p>
                                <p><strong>Nombre del billete</strong> : {ticket_name} </p>
                                <p><strong>Número de billete</strong> : {ticket_id}</p>
                                <p><strong>Correo electrónico</strong> : {email}<br></p>
                                <p><strong>URL de la aplicación</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Gracias,<br>{app_name}</p>',
    
                        'fr' => '<p>Bonjour,&nbsp;<br>Bienvenue à {app_name}.</p>
                                <p><strong>Nom du billet</strong> : {ticket_name} </p>
                                <p><strong>Numéro de billet</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>URL de lapplication</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Merci,<br>{app_name}</p>',
    
                        'it' => '<p>Ciao,&nbsp;<br>Benvenuto a {app_name}.</p>
                                <p><strong>Nome del biglietto</strong> : {ticket_name} </p>
                                <p><strong>Numero del biglietto</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>URL dellapp</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Grazie,<br>{app_name}</p>',
    
                        'ja' => '<p>こんにちは,&nbsp;<br>へようこそ {app_name}.</p>
                                <p><strong>チケット名</strong> : {ticket_name} </p>
                                <p><strong>チケット番号</strong> : {ticket_id}</p>
                                <p><strong>電子メール</strong> : {email}<br></p>
                                <p><strong>アプリのURL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>ありがとう,<br>{app_name}</p>',
    
                        'nl' => '<p>Hallo,&nbsp;<br>Welkom bij {app_name}.</p>
                                <p><strong>Ticketnaam</strong> : {ticket_name} </p>
                                <p><strong>Ticketnummer</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>App-URL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Bedankt,<br>{app_name}</p>',
    
                        'pl' => '<p>Cześć,&nbsp;<br>Witamy w {app_name}.</p>
                                <p><strong>Nazwa biletu</strong> : {ticket_name} </p>
                                <p><strong>Numer biletu</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>Adres URL aplikacji</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Dzięki,<br>{app_name}</p>',
    
                        'ru' => '<p>Привет,&nbsp;<br>Добро пожаловать в {app_name}.</p>
                                <p><strong>Название билета</strong> : {ticket_name} </p>
                                <p><strong>Номер билета</strong> : {ticket_id}</p>
                                <p><strong>Электронная почта</strong> : {email}<br></p>
                                <p><strong>URL-адрес приложения</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Спасибо,<br>{app_name}</p>',
    
                        'pt' => '<p>Olá,&nbsp;<br>Bem-vindo ao {app_name}.</p>
                                <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                                <p><strong>Número do bilhete</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>URL do aplicativo</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Obrigado,<br>{app_name}</p>',
    
                        'tr' => '<p>Merhaba,&nbsp;<br>Hoş geldiniz {app_name}.</p>
                                <p><strong>Bilet Adı</strong> : {ticket_name} </p>
                                <p><strong>Bilet Numarası</strong> : {ticket_id}</p>
                                <p><strong>E-posta</strong> : {email}<br></p>
                                <p><strong>Uygulama URLsi</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Teşekkürler,<br>{app_name}</p>',
    
                        'zh' => '<p>你好,&nbsp;<br>欢迎来到 {app_name}.</p>
                                <p><strong>票名</strong> : {ticket_name} </p>
                                <p><strong>票号</strong> : {ticket_id}</p>
                                <p><strong>电子邮件</strong> : {email}<br></p>
                                <p><strong>应用程序网址</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>谢谢,<br>{app_name}</p>',
    
                        'he' => '<p>שלום,&nbsp;<br>ברוכים הבאים ל {app_name}.</p>
                                <p><strong>שם הכרטיס</strong> : {ticket_name} </p>
                                <p><strong>מספר כרטיס</strong> : {ticket_id}</p>
                                <p><strong>אֶלֶקטרוֹנִי</strong> : {email}<br></p>
                                <p><strong>כתובת האתר של האפליקציה</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>תוֹדָה,<br>{app_name}</p>',
    
                        'pt-br' => '<p>Olá,&nbsp;<br>Bem-vindo ao {app_name}.</p>
                                <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                                <p><strong>Número do bilhete</strong> : {ticket_id}</p>
                                <p><strong>E-mail</strong> : {email}<br></p>
                                <p><strong>URL do aplicativo</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Obrigado,<br>{app_name}</p>',
                    ],
            ],
            'Send Mail To Admin' => [
                'subject' => 'Ticket Detail',
                'variables' => '{
                    "App Name": "app_name",
                    "Ticket Name": "ticket_name",
                    "Ticket Id": "ticket_id",
                    "App Url": "app_url",
                    "Customer Email": "customer_email",
                    "Agent Email": "agent_email",
                    "Ticket URL": "ticket_url"
                  }',
                  'lang' => [
                        'ar' => '<p>مرحبًا,&nbsp;<br>مرحبا بكم في {app_name}.</p>
                                <p><strong>اسم التذكرة</strong> : {ticket_name} </p>
                                <p><strong>رقم التذكرة</strong> : {ticket_id}</p>
                                <p><strong>البريد الإلكتروني للعميل </strong>: {customer_email}<br></p>
                                <p><strong>البريد الإلكتروني للوكيل  </strong>: {agent_email}<br></p>
                                <p><strong>عنوان URL للتطبيق</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>شكرًا,<br>{app_name}</p>',
    
                        'da' => '<p>Hej,&nbsp;<br>Velkommen til {app_name}.</p>
                                <p><strong>Billetnavn</strong> : {ticket_name} </p>
                                <p><strong>Billetnummer</strong> : {ticket_id}</p>
                                <p><strong>Kunde-e-mail </strong>: {customer_email}<br></p>
                                <p><strong>Agent e-mail  </strong>: {agent_email}<br></p>
                                <p><strong>App URL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Tak,<br>{app_name}</p>',
    
                        'de' => '<p>Hallo,&nbsp;<br>Willkommen bei {app_name}.</p>
                                <p><strong>Ticketname</strong> : {ticket_name} </p>
                                <p><strong>Losnummer</strong> : {ticket_id}</p>
                                <p><strong>Kunden-E-Mail </strong>: {customer_email}<br></p>
                                <p><strong>E-Mail des Agenten </strong>: {agent_email}<br></p>
                                <p><strong>App-URL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Danke,<br>{app_name}</p>',
    
                        'en' => '<p>Hello,&nbsp;<br>Welcome to {app_name}.</p>
                                <p><strong>Ticket Name</strong>: {ticket_name} </p>
                                <p><strong>Ticket Number</strong>: {ticket_id}</p>
                                <p><strong>Customer Email </strong>: {customer_email}<br></p>
                                <p><strong>Agent Email </strong>: {agent_email}<br></p>
                                <p><strong>App URL</strong>: {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Thanks,<br>{app_name}</p>',

    
                        'es' => '<p>Hola,&nbsp;<br>Bienvenido a {app_name}.</p> 
                                <p><strong>Nombre del billete</strong> : {ticket_name} </p>
                                <p><strong>Número de billete</strong> : {ticket_id}</p>
                                <p><strong>Correo electrónico del cliente </strong>: {customer_email}<br></p>
                                <p><strong>Correo electrónico del agente  </strong>: {agent_email}<br></p>
                                <p><strong>URL de la aplicación</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Gracias,<br>{app_name}</p>',
    
                        'fr' => '<p>Bonjour,&nbsp;<br>Bienvenue à {app_name}.</p>
                                <p><strong>Nom du billet</strong> : {ticket_name} </p>
                                <p><strong>Numéro de billet</strong> : {ticket_id}</p>
                                <p><strong>E-mail du client </strong>: {customer_email}<br></p>
                                <p><strong>Courriel de lagent  </strong>: {agent_email}<br></p>
                                <p><strong>URL de lapplication</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Merci,<br>{app_name}</p>',
    
                        'it' => '<p>Ciao,&nbsp;<br>Benvenuto a {app_name}.</p>
                                <p><strong>Nome del biglietto</strong> : {ticket_name} </p>
                                <p><strong>Numero del biglietto</strong> : {ticket_id}</p>
                                <p><strong>E-mail del cliente </strong>: {customer_email}<br></p>
                                <p><strong>E-mail dellagente </strong>: {agent_email}<br></p>
                                <p><strong>URL dellapp</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Grazie,<br>{app_name}</p>',
    
                        'ja' => '<p>こんにちは,&nbsp;<br>へようこそ {app_name}.</p>                                
                                <p><strong>チケット名</strong> : {ticket_name} </p>
                                <p><strong>チケット番号</strong> : {ticket_id}</p>
                                <p><strong>顧客の電子メール </strong>: {customer_email}<br></p>
                                <p><strong>エージェントの電子メール  </strong>: {agent_email}<br></p>
                                <p><strong>アプリのURL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>ありがとう,<br>{app_name}</p>',
    
                        'nl' => '<p>Hallo,&nbsp;<br>Welkom bij {app_name}.</p>
                                <p><strong>Ticketnaam</strong> : {ticket_name} </p>
                                <p><strong>Ticketnummer</strong> : {ticket_id}</p>
                                <p><strong>E-mailadres van klant </strong>: {customer_email}<br></p>
                                <p><strong>E-mailadres van agent  </strong>: {agent_email}<br></p>
                                <p><strong>App-URL</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Bedankt,<br>{app_name}</p>',
    
                        'pl' => '<p>Cześć,&nbsp;<br>Witamy w {app_name}.</p>
                                <p><strong>Nazwa biletu</strong> : {ticket_name} </p>
                                <p><strong>Numer biletu</strong> : {ticket_id}</p>
                                <p><strong>E-mail klienta </strong>: {customer_email}<br></p>
                                <p><strong>E-mail agenta  </strong>: {agent_email}<br></p>
                                <p><strong>Adres URL aplikacji</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Dzięki,<br>{app_name}</p>',
    
                        'ru' => '<p>Привет,&nbsp;<br>Добро пожаловать в {app_name}.</p>
                                <p><strong>Название билета</strong> : {ticket_name} </p>
                                <p><strong>Номер билета</strong> : {ticket_id}</p>
                                <p><strong>Электронная почта клиента </strong>: {customer_email}<br></p>
                                <p><strong>Электронная почта агента  </strong>: {agent_email}<br></p>
                                <p><strong>URL-адрес приложения</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Спасибо,<br>{app_name}</p>',
    
                        'pt' => '<p>Olá,&nbsp;<br>Bem-vindo ao {app_name}.</p>
                                <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                                <p><strong>Número do bilhete</strong> : {ticket_id}</p>
                                <p><strong>E-mail do cliente </strong>: {customer_email}<br></p>
                                <p><strong>E-mail do agente  </strong>: {agent_email}<br></p>
                                <p><strong>URL do aplicativo</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Obrigado,<br>{app_name}</p>',
    
                        'tr' => '<p>Merhaba,&nbsp;<br>Hoş geldiniz {app_name}.</p>                                 
                                <p><strong>Bilet Adı</strong> : {ticket_name} </p>
                                <p><strong>Bilet Numarası</strong> : {ticket_id}</p>
                                <p><strong>Müşteri E-postası </strong>: {customer_email}<br></p>
                                <p><strong>Temsilci E-postası  </strong>: {agent_email}<br></p>
                                <p><strong>Uygulama URLsi</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Teşekkürler,<br>{app_name}</p>',
    
                        'zh' => '<p>你好,&nbsp;<br>欢迎来到 {app_name}.</p>
                                <p><strong>票名</strong> : {ticket_name} </p>
                                <p><strong>票号</strong> : {ticket_id}</p>
                                <p><strong>客户邮箱 </strong>: {customer_email}<br></p>
                                <p><strong>代理邮箱  </strong>: {agent_email}<br></p>
                                <p><strong>应用程序网址</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>谢谢,<br>{app_name}</p>',
    
                        'he' => '<p>שלום,&nbsp;<br>ברוכים הבאים ל {app_name}.</p>
                                <p><strong>שם הכרטיס</strong> : {ticket_name} </p>
                                <p><strong>מספר כרטיס</strong> : {ticket_id}</p>
                                <p><strong>אימייל ללקוח </strong>: {customer_email}<br></p>
                                <p><strong>אימייל של סוכן  </strong>: {agent_email}<br></p>
                                <p><strong>כתובת האתר של האפליקציה</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>תוֹדָה,<br>{app_name}</p>',
    
                        'pt-br' => '<p>Olá,&nbsp;<br>Bem-vindo ao {app_name}.</p>
                                <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                                <p><strong>Número do bilhete</strong> : {ticket_id}</p>
                                <p><strong>E-mail do cliente </strong>: {customer_email}<br></p>
                                <p><strong>E-mail do agente  </strong>: {agent_email}<br></p>
                                <p><strong>URL do aplicativo</strong> {app_url}</p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p>Obrigado,<br>{app_name}</p>',
                    ],
            ],
            'Reply Mail To Customer' => [
                'subject' => 'Ticket Reply',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "Ticket Name": "ticket_name",
                    "Ticket Id": "ticket_id",
                    "Ticket Description": "ticket_description"
                  }',
                'lang' => [
                    'ar' => '<p>مرحبًا,&nbsp;<br/>مرحبا بكم في {app_name}.</p>
                            <p><strong>اسم التذكرة </strong> : {ticket_name}</p>
                            <p><strong>رقم التذكرة</strong> : {ticket_id}</p>
                            <p><strong>وصف</strong> : {ticket_description} </p>
                            <p>شكرا ,<br />{app_name}</p>',
                    'da' => '<p>Hej,&nbsp;<br/> velkommen til {app_name}.</p>
                            <p><strong>Billetnavn</strong> {ticket_name} </p>
                            <p><strong>Billetnummer</strong> {ticket_id} </p>
                            <p><strong>Beskrivelse</strong> : {ticket_description} </p>
                            <p>Tak.,<br />{app_name}</p>',
                    'de' => '<p>Hallo,&nbsp;<br/>Willkommen bei {app_name}.</p>
                            <p><strong>Ticketname</strong> : {ticketname}</p>
                            <p><strong>Losnummer</strong> : {ticket_id}</p>
                            <p><strong>Beschreibung</strong> : {ticket_description}</p>
                            <p>Danke,<br />{Anwendungsname}</p>',
                    'en' => '<p>Hello,&nbsp;<br/>Welcome to {app_name}.</p>
                            <p><strong>Ticket Name</strong> : {ticket_name} </p>
                            <p><strong>Ticket Number</strong> : {ticket_id} </p>
                            <p><strong>Description</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name}</p>',
                    'es' => '<p>Hola,&nbsp;<br/> Bienvenido a {app_name}.</p>
                            <p><strong>Nombre del billete</strong> : {ticket_name} </p>
                            <p><strong>Número de billete</strong> : {ticket_id} </p>
                            <p><strong>Descripción</strong> : {ticket_description} </p>
                            <p>Gracias,<br />{app_name}</p>',
                    'fr' => '<p>Hola,&nbsp;<br/> Bienvenido a {app_name}. </p>
                            <p><strong>Nom du billet</strong> : {ticket_name} </p>
                            <p><strong>Numéro de billet</strong> : {ticket_id} </p>
                            <p><strong>Description</strong> : {ticket_description} </p>
                            <p>Gracias,<br />{app_name}</p>',
                    'it' => '<p>Ciao,&nbsp;<br/> Benvenuti in {app_name}. </p>
                            <p><strong>Nome del biglietto</strong> : {ticket_name} </p>
                            <p><strong>Numero del biglietto</strong> : {ticket_id} </p>
                            <p><strong>Descrizione</strong>: {ticket_description} </p>
                            <p>Grazie,<br />{app_name}</p>',
                    'ja' => '<p>こんにちは,&nbsp;<br/> {app_name}へようこそ。</p>
                            <p><strong>チケット名</strong> : {ticket_name} </p>
                            <p><strong>チケット名</strong> : {ticket_id} </p>
                            <p><strong>説明</strong> : {ticket_description} </p>
                            <p>ありがとう,<br />{app_name}</p>',
                    'nl' => '<p>Hallo,&nbsp;<br/> Welkom bij {app_name}. </p>
                            <p><strong>Ticketnaam</strong> : {ticket_name} </p>
                            <p><strong>Ticketnummer</strong> : {ticket_id} </p>
                            <p><strong>Beschrijving</strong> : {ticket_description} </p>
                            <p>Bedankt,<br />{app_name}</p>',
                    'pl' => '<p>Witaj,&nbsp;<br/> Witamy w aplikacji {app_name}. </p>
                            <p><strong>Nazwa biletu</strong> : {ticket_name} </p>
                            <p><strong>Numer biletu</strong> : {ticket_id} </p>
                            <p><strong>Opis</strong> : {ticket_description} </p>
                            <p>Dziękuję,<br />{app_name}</p>',
                    'ru' => '<p>Здравствуйте,&nbsp;<br/> Добро пожаловать в {app_name}. </p>
                            <p>Witaj, Witamy w aplikacji {app_name}. </p>
                            <p><strong>Название билета</strong> : {ticket_name} </p>
                            <p><strong>Номер билета</strong> : {ticket_id}</p>
                            <p><strong>Описание</strong> : {ticket_description} </p>
                            <p>Dziękuję,<br />{app_name} </p>',
                    'pt' => '<p>Ol&aacute;,&nbsp;<br/> Bem-vindo a {app_name}. </p>
                            <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                            <p><strong>Número do bilhete</strong> : {ticket_id} </p>
                            <p><strong>Descrição</strong> : {ticket_description} </p>
                            <p>Obrigado,<br />{app_name} </p>',
                    'tr' => '<p>Ol,&nbsp;<br/> {app_name} olanağına hoş geldiniz.</p>
                            <p><strong>Bilet Adı</strong> : {ticket_name}</p>
                            <p><strong>Bilet Numarası</strong> : {ticket_id}</p>
                            <p><strong>Tanım</strong> : {ticket_description}</p>
                            <p>Teşekkür ederim,<br />{app_name}</p>',
                    'he' => '<p>שלום,&nbsp;<br/>ברוכים הבאים ל {app_name}. </p>
                            <p><strong>שם הכרטיס</strong> : {ticket_name} </p>
                            <p><strong>מספר כרטיס</strong> : {ticket_id} </p>
                            <p><strong>תֵאוּר</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name} </p>',
                    'zh' => '<p>你好,&nbsp;<br/>这是很好的例子。 {app_name}. </p>
                            <p><strong>票名</strong> : {ticket_name} </p>
                            <p><strong>描述</strong> : {ticket_id} </p>
                            <p><strong>描述</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name} </p>',
                   'pt-br' => '<p>Olá,&nbsp;<br/>Bem-vindo ao {app_name}. </p>
                            <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                            <p><strong>Número do bilhete</strong> : {ticket_id} </p>
                            <p><strong>Descrição</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name} </p>',
                ],
            ],
            'Reply Mail To Agent' => [
                'subject' => 'Ticket Reply',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "Ticket Name": "ticket_name",
                    "Ticket Id": "ticket_id",
                    "Ticket Description": "ticket_description"
                  }',
                'lang' => [
                    'ar' => '<p>مرحبًا,&nbsp;<br/>مرحبا بكم في {app_name}.</p>
                            <p><strong>اسم التذكرة </strong> : {ticket_name}</p>
                            <p><strong>رقم التذكرة</strong> : {ticket_id}</p>
                            <p><strong>وصف</strong> : {ticket_description} </p>
                            <p>شكرا ,<br />{app_name}</p>',
                    'da' => '<p>Hej,&nbsp;<br/> velkommen til {app_name}.</p>
                            <p><strong>Billetnavn</strong> {ticket_name} </p>
                            <p><strong>Billetnummer</strong> {ticket_id} </p>
                            <p><strong>Beskrivelse</strong> : {ticket_description} </p>
                            <p>Tak.,<br />{app_name}</p>',
                    'de' => '<p>Hallo,&nbsp;<br/>Willkommen bei {app_name}.</p>
                            <p><strong>Ticketname</strong> : {ticketname}</p>
                            <p><strong>Losnummer</strong> : {ticket_id}</p>
                            <p><strong>Beschreibung</strong> : {ticket_description}</p>
                            <p>Danke,<br />{Anwendungsname}</p>',
                    'en' => '<p>Hello,&nbsp;<br/>Welcome to {app_name}.</p>
                            <p><strong>Ticket Name</strong> : {ticket_name} </p>
                            <p><strong>Ticket Number</strong> : {ticket_id} </p>
                            <p><strong>Description</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name}</p>',
                    'es' => '<p>Hola,&nbsp;<br/> Bienvenido a {app_name}.</p>
                            <p><strong>Nombre del billete</strong> : {ticket_name} </p>
                            <p><strong>Número de billete</strong> : {ticket_id} </p>
                            <p><strong>Descripción</strong> : {ticket_description} </p>
                            <p>Gracias,<br />{app_name}</p>',
                    'fr' => '<p>Hola,&nbsp;<br/> Bienvenido a {app_name}. </p>
                            <p><strong>Nom du billet</strong> : {ticket_name} </p>
                            <p><strong>Numéro de billet</strong> : {ticket_id} </p>
                            <p><strong>Description</strong> : {ticket_description} </p>
                            <p>Gracias,<br />{app_name}</p>',
                    'it' => '<p>Ciao,&nbsp;<br/> Benvenuti in {app_name}. </p>
                            <p><strong>Nome del biglietto</strong> : {ticket_name} </p>
                            <p><strong>Numero del biglietto</strong> : {ticket_id} </p>
                            <p><strong>Descrizione</strong>: {ticket_description} </p>
                            <p>Grazie,<br />{app_name}</p>',
                    'ja' => '<p>こんにちは,&nbsp;<br/> {app_name}へようこそ。</p>
                            <p><strong>チケット名</strong> : {ticket_name} </p>
                            <p><strong>チケット名</strong> : {ticket_id} </p>
                            <p><strong>説明</strong> : {ticket_description} </p>
                            <p>ありがとう,<br />{app_name}</p>',
                    'nl' => '<p>Hallo,&nbsp;<br/> Welkom bij {app_name}. </p>
                            <p><strong>Ticketnaam</strong> : {ticket_name} </p>
                            <p><strong>Ticketnummer</strong> : {ticket_id} </p>
                            <p><strong>Beschrijving</strong> : {ticket_description} </p>
                            <p>Bedankt,<br />{app_name}</p>',
                    'pl' => '<p>Witaj,&nbsp;<br/> Witamy w aplikacji {app_name}. </p>
                            <p><strong>Nazwa biletu</strong> : {ticket_name} </p>
                            <p><strong>Numer biletu</strong> : {ticket_id} </p>
                            <p><strong>Opis</strong> : {ticket_description} </p>
                            <p>Dziękuję,<br />{app_name}</p>',
                    'ru' => '<p>Здравствуйте,&nbsp;<br/> Добро пожаловать в {app_name}. </p>
                            <p>Witaj, Witamy w aplikacji {app_name}. </p>
                            <p><strong>Название билета</strong> : {ticket_name} </p>
                            <p><strong>Номер билета</strong> : {ticket_id}</p>
                            <p><strong>Описание</strong> : {ticket_description} </p>
                            <p>Dziękuję,<br />{app_name} </p>',
                    'pt' => '<p>Ol&aacute;,&nbsp;<br/> Bem-vindo a {app_name}. </p>
                            <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                            <p><strong>Número do bilhete</strong> : {ticket_id} </p>
                            <p><strong>Descrição</strong> : {ticket_description} </p>
                            <p>Obrigado,<br />{app_name} </p>',
                    'tr' => '<p>Ol,&nbsp;<br/> {app_name} olanağına hoş geldiniz.</p>
                            <p><strong>Bilet Adı</strong> : {ticket_name}</p>
                            <p><strong>Bilet Numarası</strong> : {ticket_id}</p>
                            <p><strong>Tanım</strong> : {ticket_description}</p>
                            <p>Teşekkür ederim,<br />{app_name}</p>',
                    'he' => '<p>שלום,&nbsp;<br/>ברוכים הבאים ל {app_name}. </p>
                            <p><strong>שם הכרטיס</strong> : {ticket_name} </p>
                            <p><strong>מספר כרטיס</strong> : {ticket_id} </p>
                            <p><strong>תֵאוּר</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name} </p>',
                    'zh' => '<p>你好,&nbsp;<br/>这是很好的例子。 {app_name}. </p>
                            <p><strong>票名</strong> : {ticket_name} </p>
                            <p><strong>描述</strong> : {ticket_id} </p>
                            <p><strong>描述</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name} </p>',
                   'pt-br' => '<p>Olá,&nbsp;<br/>Bem-vindo ao {app_name}. </p>
                            <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                            <p><strong>Número do bilhete</strong> : {ticket_id} </p>
                            <p><strong>Descrição</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name} </p>',
                ],
            ],
            'Reply Mail To Admin' => [
                'subject' => 'Ticket Reply',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "Ticket Name": "ticket_name",
                    "Customer Email": "customer_email",
                    "Agent Email": "agent_email",
                    "Ticket Id": "ticket_id",
                    "Ticket Description": "ticket_description"
                  }',
                'lang' => [
                    'ar' => '<p>مرحبًا,&nbsp;<br/>مرحبا بكم في {app_name}.</p>
                            <p><strong>اسم التذكرة </strong> : {ticket_name}</p>
                            <p><strong>رقم التذكرة</strong> : {ticket_id}</p>
                            <p><strong>البريد الإلكتروني للعميل </strong>: {customer_email}<br></p>
                            <p><strong>البريد الإلكتروني للوكيل  </strong>: {agent_email}<br></p>
                            <p><strong>وصف</strong> : {ticket_description} </p>
                            <p>شكرا ,<br />{app_name}</p>',
                    'da' => '<p>Hej,&nbsp;<br/> velkommen til {app_name}.</p>
                            <p><strong>Billetnavn</strong> {ticket_name} </p>
                            <p><strong>Billetnummer</strong> {ticket_id} </p>
                            <p><strong>Kunde-e-mail </strong>: {customer_email}<br></p>
                           <p><strong>Agent e-mail  </strong>: {agent_email}<br></p>
                            <p><strong>Beskrivelse</strong> : {ticket_description} </p>
                            <p>Tak.,<br />{app_name}</p>',
                    'de' => '<p>Hallo,&nbsp;<br/>Willkommen bei {app_name}.</p>
                            <p><strong>Ticketname</strong> : {ticketname}</p>
                            <p><strong>Losnummer</strong> : {ticket_id}</p>
                            <p><strong>Kunden-E-Mail </strong>: {customer_email}<br></p>
                            <p><strong>E-Mail des Agenten </strong>: {agent_email}<br></p>
                            <p><strong>Beschreibung</strong> : {ticket_description}</p>
                            <p>Danke,<br />{Anwendungsname}</p>',
                    'en' => '<p>Hello,&nbsp;<br/>Welcome to {app_name}.</p>
                            <p><strong>Ticket Name</strong> : {ticket_name} </p>
                            <p><strong>Ticket Number</strong> : {ticket_id} </p>
                            <p><strong>Customer Email </strong>: {customer_email}<br></p>
                            <p><strong>Agent Email </strong>: {agent_email}<br></p>
                            <p><strong>Description</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name}</p>',
                    'es' => '<p>Hola,&nbsp;<br/> Bienvenido a {app_name}.</p>
                            <p><strong>Nombre del billete</strong> : {ticket_name} </p>
                            <p><strong>Número de billete</strong> : {ticket_id} </p>
                            <p><strong>Correo electrónico del cliente </strong>: {customer_email}<br></p>
                            <p><strong>Correo electrónico del agente  </strong>: {agent_email}<br></p>
                            <p><strong>Descripción</strong> : {ticket_description} </p>
                            <p>Gracias,<br />{app_name}</p>',
                    'fr' => '<p>Hola,&nbsp;<br/> Bienvenido a {app_name}. </p>
                            <p><strong>Nom du billet</strong> : {ticket_name} </p>
                            <p><strong>Numéro de billet</strong> : {ticket_id} </p>
                            <p><strong>E-mail du client </strong>: {customer_email}<br></p>
                            <p><strong>Courriel de lagent  </strong>: {agent_email}<br></p>
                            <p><strong>Description</strong> : {ticket_description} </p>
                            <p>Gracias,<br />{app_name}</p>',
                    'it' => '<p>Ciao,&nbsp;<br/> Benvenuti in {app_name}. </p>
                            <p><strong>Nome del biglietto</strong> : {ticket_name} </p>
                            <p><strong>Numero del biglietto</strong> : {ticket_id} </p>
                            <p><strong>E-mail del cliente </strong>: {customer_email}<br></p>
                            <p><strong>E-mail dellagente </strong>: {agent_email}<br></p>
                            <p><strong>Descrizione</strong>: {ticket_description} </p>
                            <p>Grazie,<br />{app_name}</p>',
                    'ja' => '<p>こんにちは,&nbsp;<br/> {app_name}へようこそ。</p>
                            <p><strong>チケット名</strong> : {ticket_name} </p>
                            <p><strong>チケット名</strong> : {ticket_id} </p>
                            <p><strong>顧客の電子メール </strong>: {customer_email}<br></p>
                            <p><strong>エージェントの電子メール  </strong>: {agent_email}<br></p>
                            <p><strong>説明</strong> : {ticket_description} </p>
                            <p>ありがとう,<br />{app_name}</p>',
                    'nl' => '<p>Hallo,&nbsp;<br/> Welkom bij {app_name}. </p>
                            <p><strong>Ticketnaam</strong> : {ticket_name} </p>
                            <p><strong>Ticketnummer</strong> : {ticket_id} </p>
                            <p><strong>E-mailadres van klant </strong>: {customer_email}<br></p>
                            <p><strong>E-mailadres van agent  </strong>: {agent_email}<br></p>
                            <p><strong>Beschrijving</strong> : {ticket_description} </p>
                            <p>Bedankt,<br />{app_name}</p>',
                    'pl' => '<p>Witaj,&nbsp;<br/> Witamy w aplikacji {app_name}. </p>
                            <p><strong>Nazwa biletu</strong> : {ticket_name} </p>
                            <p><strong>Numer biletu</strong> : {ticket_id} </p>
                            <p><strong>E-mail klienta </strong>: {customer_email}<br></p>
                            <p><strong>E-mail agenta  </strong>: {agent_email}<br></p>
                            <p><strong>Opis</strong> : {ticket_description} </p>
                            <p>Dziękuję,<br />{app_name}</p>',
                    'ru' => '<p>Здравствуйте,&nbsp;<br/> Добро пожаловать в {app_name}. </p>
                            <p>Witaj, Witamy w aplikacji {app_name}. </p>
                            <p><strong>Название билета</strong> : {ticket_name} </p>
                            <p><strong>Номер билета</strong> : {ticket_id}</p>
                            <p><strong>Электронная почта клиента </strong>: {customer_email}<br></p>
                            <p><strong>Электронная почта агента  </strong>: {agent_email}<br></p>
                            <p><strong>Описание</strong> : {ticket_description} </p>
                            <p>Dziękuję,<br />{app_name} </p>',
                    'pt' => '<p>Ol&aacute;,&nbsp;<br/> Bem-vindo a {app_name}. </p>
                            <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                            <p><strong>Número do bilhete</strong> : {ticket_id} </p>
                            <p><strong>E-mail do cliente </strong>: {customer_email}<br></p>
                            <p><strong>E-mail do agente  </strong>: {agent_email}<br></p>
                            <p><strong>Descrição</strong> : {ticket_description} </p>
                            <p>Obrigado,<br />{app_name} </p>',
                    'tr' => '<p>Ol,&nbsp;<br/> {app_name} olanağına hoş geldiniz.</p>
                            <p><strong>Bilet Adı</strong> : {ticket_name}</p>
                            <p><strong>Bilet Numarası</strong> : {ticket_id}</p>
                            <p><strong>Müşteri E-postası </strong>: {customer_email}<br></p>
                            <p><strong>Temsilci E-postası  </strong>: {agent_email}<br></p>
                            <p><strong>Tanım</strong> : {ticket_description}</p>
                            <p>Teşekkür ederim,<br />{app_name}</p>',
                    'he' => '<p>שלום,&nbsp;<br/>ברוכים הבאים ל {app_name}. </p>
                            <p><strong>שם הכרטיס</strong> : {ticket_name} </p>
                            <p><strong>מספר כרטיס</strong> : {ticket_id} </p>
                            <p><strong>אימייל ללקוח </strong>: {customer_email}<br></p>
                            <p><strong>אימייל של סוכן  </strong>: {agent_email}<br></p>
                            <p><strong>תֵאוּר</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name} </p>',
                    'zh' => '<p>你好,&nbsp;<br/>这是很好的例子。 {app_name}. </p>
                            <p><strong>票名</strong> : {ticket_name} </p>
                            <p><strong>描述</strong> : {ticket_id} </p>
                            <p><strong>客户邮箱 </strong>: {customer_email}<br></p>
                            <p><strong>代理邮箱  </strong>: {agent_email}<br></p>
                            <p><strong>描述</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name} </p>',
                   'pt-br' => '<p>Olá,&nbsp;<br/>Bem-vindo ao {app_name}. </p>
                            <p><strong>Nome do bilhete</strong> : {ticket_name} </p>
                            <p><strong>Número do bilhete</strong> : {ticket_id} </p>
                            <p><strong>E-mail do cliente </strong>: {customer_email}<br></p>
                            <p><strong>E-mail do agente  </strong>: {agent_email}<br></p>
                            <p><strong>Descrição</strong> : {ticket_description} </p>
                            <p>Thanks,<br />{app_name} </p>',
                ],
            ],
            'Ticket Close' => [
                'subject' => 'Ticket Detail',
                'variables' => '{
                    "App Name": "app_name",
                    "Ticket Name": "ticket_name",
                    "Ticket Id": "ticket_id",
                    "App Url": "app_url",
                    "Ticket URL": "ticket_url"
                  }',
                  'lang' => [
                        'ar' => '<p>مرحبًا،<br>مرحبًا بك في {app_name}.</p>
                                <p><strong>اسم التذكرة</strong> : {ticket_name}</p>
                                <p><strong>رقم التذكرة</strong> : {ticket_id}</p>
                                <p><strong>تم إغلاق تذكرتك <b>{ticket_id}</b>. لمزيد من التفاصيل، يمكنك التحقق من تذكرتك عبر الرابط أدناه.</strong></p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">فتح التذكرة</a></p>
                                <p><strong>رابط التطبيق</strong>: {app_url}</p>
                                <p>شكرًا،<br>{app_name}</p>',
    
                        'da' => '<p>Hej, <br>Velkommen til {app_name}.</p>
                                <p><strong>Billetnavn</strong> : {ticket_name}</p>
                                <p><strong>Billetnummer</strong> : {ticket_id}</p>
                                <p><strong>Din billet <b>{ticket_id}</b> er blevet lukket. For flere detaljer kan du tjekke din billet via nedenstående link.</strong></p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Åbn billet</a></p>
                                <p><strong>App URL</strong>: {app_url}</p>
                                <p>Tak,<br>{app_name}</p>',
    
                        'de' => '<p>Hallo, <br>Willkommen bei {app_name}.</p>
                                 <p><strong>Ticketname</strong> : {ticket_name}</p>
                                 <p><strong>Ticketnummer</strong> : {ticket_id}</p>
                                 <p><strong>Ihr Ticket <b>{ticket_id}</b> wurde geschlossen. Für weitere Details können Sie Ihr Ticket über den untenstehenden Link überprüfen.</strong></p>
                                 <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Ticket öffnen</a></p>
                                 <p><strong>App-URL</strong>: {app_url}</p>
                                 <p>Danke,<br>{app_name}</p>',
                        'en' => '<p>Hello,&nbsp;<br>Welcome to {app_name}.</p>
                                <p><strong>Ticket Name</strong> : {ticket_name} </p>
                                <p><strong>Ticket Number</strong> : {ticket_id}</p>
                                <p><strong>Your Ticket <b>{ticket_id}</b> Has Been Closed. For More Details You Can Check Your Ticket Via Below Link.</strong> </p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                                <p><strong>App URL</strong>: {app_url}</p>
                                <p>Thanks,<br>{app_name}</p>',
    
                        'es' => '<p>Hola, <br>Bienvenido a {app_name}.</p>
                                 <p><strong>Nombre del ticket</strong> : {ticket_name}</p>
                                 <p><strong>Número de ticket</strong> : {ticket_id}</p>
                                 <p><strong>Su ticket <b>{ticket_id}</b> ha sido cerrado. Para más detalles, puede consultar su ticket a través del siguiente enlace.</strong></p>
                                 <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Abrir ticket</a></p>
                                 <p><strong>URL de la aplicación</strong>: {app_url}</p>
                                 <p>Gracias,<br>{app_name}</p>',
    
                        'fr' => '<p>Bonjour, <br>Bienvenue sur {app_name}.</p>
                                 <p><strong>Nom du ticket</strong> : {ticket_name}</p>
                                 <p><strong>Numéro du ticket</strong> : {ticket_id}</p>
                                 <p><strong>Votre ticket <b>{ticket_id}</b> a été fermé. Pour plus de détails, vous pouvez consulter votre ticket via le lien ci-dessous.</strong></p>
                                 <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Ouvrir le ticket</a></p>
                                 <p><strong>URL de lapplication</strong>: {app_url}</p>
                                 <p>Merci,<br>{app_name}</p>',
    
                        'it' => '<p>Ciao, <br>Benvenuto su {app_name}.</p>
                                <p><strong>Nome del ticket</strong> : {ticket_name}</p>
                                <p><strong>Numero del ticket</strong> : {ticket_id}</p>
                                <p><strong>Il tuo ticket <b>{ticket_id}</b> è stato chiuso. Per maggiori dettagli, puoi controllare il tuo ticket tramite il link sottostante.</strong></p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Apri il ticket</a></p>
                                <p><strong>URL dellapp</strong>: {app_url}</p>
                                <p>Grazie,<br>{app_name}</p>',
    
                        'ja' => '<p>こんにちは、<br>{app_name}へようこそ。</p>
                                <p><strong>チケット名</strong> : {ticket_name}</p>
                                <p><strong>チケット番号</strong> : {ticket_id}</p>
                                <p><strong>あなたのチケット <b>{ticket_id}</b> はクローズされました。詳細については、以下のリンクからチケットを確認できます。</strong></p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">チケットを開く</a></p>
                                <p><strong>アプリのURL</strong>: {app_url}</p>
                                <p>ありがとうございます。<br>{app_name}</p>',
    
                        'nl' => '<p>Hallo, <br>Welkom bij {app_name}.</p>
                                 <p><strong>Ticketnaam</strong> : {ticket_name}</p>
                                 <p><strong>Ticketnummer</strong> : {ticket_id}</p>
                                 <p><strong>Je ticket <b>{ticket_id}</b> is gesloten. Voor meer details kun je je ticket bekijken via de onderstaande link.</strong></p>
                                 <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Ticket openen</a></p>
                                 <p><strong>App-URL</strong>: {app_url}</p>
                                 <p>Bedankt,<br>{app_name}</p>',
    
                        'pl' => '<p>Cześć, <br>Witamy w {app_name}.</p>
                                 <p><strong>Nazwa zgłoszenia</strong> : {ticket_name}</p>
                                 <p><strong>Numer zgłoszenia</strong> : {ticket_id}</p>
                                 <p><strong>Twoje zgłoszenie <b>{ticket_id}</b> zostało zamknięte. Aby uzyskać więcej szczegółów, możesz sprawdzić swoje zgłoszenie za pomocą poniższego linku.</strong></p>
                                 <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Otwórz zgłoszenie</a></p>
                                 <p><strong>URL aplikacji</strong>: {app_url}</p>
                                 <p>Dziękujemy,<br>{app_name}</p>',
    
                        'ru' => '<p>Здравствуйте, <br>Добро пожаловать в {app_name}.</p>
                                 <p><strong>Название заявки</strong> : {ticket_name}</p>
                                 <p><strong>Номер заявки</strong> : {ticket_id}</p>
                                 <p><strong>Ваша заявка <b>{ticket_id}</b> была закрыта. Для получения дополнительной информации вы можете проверить свою заявку по ссылке ниже.</strong></p>
                                 <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Открыть заявку</a></p>
                                 <p><strong>URL приложения</strong>: {app_url}</p>
                                 <p>Спасибо,<br>{app_name}</p>',
    
                        'pt' => '<p>Olá, <br>Bem-vindo ao {app_name}.</p>
                                <p><strong>Nome do ticket</strong> : {ticket_name}</p>
                                <p><strong>Número do ticket</strong> : {ticket_id}</p>
                                <p><strong>Seu ticket <b>{ticket_id}</b> foi encerrado. Para mais detalhes, você pode verificar seu ticket através do link abaixo.</strong></p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Abrir ticket</a></p>
                                <p><strong>URL do aplicativo</strong>: {app_url}</p>
                                <p>Obrigado,<br>{app_name}</p>',
    
                        'tr' => '<p>Merhaba, <br>{app_name} e hoş geldiniz.</p>
                                 <p><strong>Bilet Adı</strong> : {ticket_name}</p>
                                 <p><strong>Bilet Numarası</strong> : {ticket_id}</p>
                                 <p><strong>Biletiniz <b>{ticket_id}</b> kapatıldı. Daha fazla detay için aşağıdaki bağlantıdan biletinizi kontrol edebilirsiniz.</strong></p>
                                 <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Bileti Aç</a></p>
                                 <p><strong>Uygulama URL si</strong>: {app_url}</p>
                                 <p>Teşekkürler,<br>{app_name}</p>',
    
                        'zh' => '<p>您好，<br>欢迎使用 {app_name}。</p>
                                 <p><strong>工单名称</strong> : {ticket_name}</p>
                                 <p><strong>工单编号</strong> : {ticket_id}</p>
                                 <p><strong>您的工单 <b>{ticket_id}</b> 已关闭。更多详情，请通过以下链接查看您的工单。</strong></p>
                                 <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">打开工单</a></p>
                                 <p><strong>应用链接</strong>: {app_url}</p>
                                 <p>谢谢，<br>{app_name}</p>',
    
                        'he' => '<p>שלום,<br>ברוכים הבאים ל-{app_name}.</p>
                                <p><strong>שם הכרטיס</strong> : {ticket_name}</p>
                                <p><strong>מספר הכרטיס</strong> : {ticket_id}</p>
                                <p><strong>הכרטיס שלך <b>{ticket_id}</b> נסגר. למידע נוסף, ניתן לבדוק את הכרטיס שלך דרך הקישור למטה.</strong></p>
                                <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">פתח כרטיס</a></p>
                                <p><strong>כתובת האפליקציה</strong>: {app_url}</p>
                                <p>תודה,<br>{app_name}</p>',
    
                        'pt-br' => '<p>Olá, <br>Bem-vindo ao {app_name}.</p>
                                   <p><strong>Nome do chamado</strong> : {ticket_name}</p>
                                   <p><strong>Número do chamado</strong> : {ticket_id}</p>
                                   <p><strong>O seu chamado <b>{ticket_id}</b> foi encerrado. Para mais detalhes, você pode acessar o chamado através do link abaixo.</strong></p>
                                   <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Abrir chamado</a></p>
                                   <p><strong>URL do aplicativo</strong>: {app_url}</p>
                                   <p>Obrigado,<br>{app_name}</p>',
                    ],
            ],

        ];

        foreach ($emailTemplates as $emailTemplate => $action) {
            $ntfy = NotificationTemplates::where('action', $action)->where('type', 'mail')->where('module', 'General')->count();
            if ($ntfy == 0) {
                $new = new NotificationTemplates();
                $new->action = $action;
                $new->module = 'General';
                $new->type = 'mail';
                $new->from = 'TicketGo';
                $new->save();

                foreach ($defaultTemplate[$action]['lang'] as $lang => $content) {
                    NotificationTemplateLangs::create(
                        [
                            'parent_id' => $new->id,
                            'lang'      => $lang,
                            'module'    => $new->module,
                            'variables' => $defaultTemplate[$action]['variables'],
                            'subject'   => $defaultTemplate[$action]['subject'],
                            'content'   => $content,
                        ]
                    );
                }
            }
        }

        // Code For Remove New Ticket & New Ticket Reply Email Template 
        $emailTemplates = NotificationTemplates::whereIn('action',['New Ticket','New Ticket Reply'])->where('type', 'mail')->get();
        if(count($emailTemplates) > 0){
           foreach($emailTemplates as $emailTemplate){
              $getTemplateDetails = NotificationTemplateLangs::where('parent_id',$emailTemplate->id)->get();
              if(count($getTemplateDetails) > 0){
                    foreach($getTemplateDetails as $templateDetails) {
                           $templateDetails->delete();
                    }
              }
              $emailTemplate->delete();
           }
        }
    }
}
