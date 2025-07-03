<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NotificationTemplates;
use App\Models\NotificationTemplateLangs;

class NewTemplateNotification extends Seeder
{
    public function run()
    {
        $emailTemplates = [
            'Send Mail To Creator',
            'Reply Mail To Creator',
        ];
            
        // Definición completa de todas las plantillas
        $defaultTemplate = [
            'Send Mail To Creator' => [
                'subject' => 'Ticket Creado',
                'variables' => '{
                    "App Name" : "app_name",
                    "Customer Name" : "customer_name",
                    "Customer Email" : "customer_email",
                    "Ticket Id" : "ticket_id",
                    "Category" : "ticket_category",
                    "Priority" : "ticket_priority",
                    "Department" : "ticket_department",
                    "Ticket Subject" : "ticket_subject",
                    "Description" : "ticket_description",
                    "Ticket URL" : "ticket_url",
                    "Resolution Agent Name" : "resolution_agent_name",
                    "Support Agent Name" : "support_agent_name",
                    "App Url" : "app_url"
                }',
                'lang' => [
                    'en' => '<p>Hola estimado(a) {support_agent_name},</p>
                            <p>Se le ha asignado un ticket, aquí están los detalles:</p>
                            <p><b>🔹 Número de ticket</b>: {ticket_id}</p>
                            <p><b>🔹 Asunto del ticket</b>: {ticket_subject}</p>
                            <p><b>🔹 Prioridad</b>: {ticket_priority}</p>
                            <p><b>🔹 Categoría</b>: {ticket_category}</p>
                            <p><b>🔹 Proceso Afectado</b>: {ticket_department}</p>
                            <p><b>🧑‍💻 Agente de Resolución</b>:</p>
                            <p><b>Nombre</b>: {resolution_agent_name}</p>
                            <p><b>🧑‍💼 Solicitante</b>:</p>
                            <p><b>Nombre</b>: {customer_name}</p>
                            <p><b>Correo electrónico</b>: {customer_email}</p>
                            <p><b>📝 Descripción del ticket</b>:</p>
                            <div>{ticket_description}</div>
                            <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                            <p><b>URL de la Aplicación</b>: {app_url}</p>
                            <p>Este correo fue generado automáticamente por {app_name}. No es necesario responder a este mensaje.</p>',
                    'es' => '<p>Hola estimado(a) {support_agent_name},</p>
                            <p>Se le ha asignado un ticket, aquí están los detalles:</p>
                            <p><b>🔹 Número de ticket</b>: {ticket_id}</p>
                            <p><b>🔹 Asunto del ticket</b>: {ticket_subject}</p>
                            <p><b>🔹 Prioridad</b>: {ticket_priority}</p>
                            <p><b>🔹 Categoría</b>: {ticket_category}</p>
                            <p><b>🔹 Proceso Afectado</b>: {ticket_department}</p>
                            <p><b>🧑‍💻 Agente de Resolución</b>:</p>
                            <p><b>Nombre</b>: {resolution_agent_name}</p>
                            <p><b>🧑‍💼 Solicitante</b>:</p>
                            <p><b>Nombre</b>: {customer_name}</p>
                            <p><b>Correo electrónico</b>: {customer_email}</p>
                            <p><b>📝 Descripción del ticket</b>:</p>
                            <div>{ticket_description}</div>
                            <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Abrir Ticket</a></p>
                            <p><b>URL de la Aplicación</b>: {app_url}</p>
                            <p>Este correo fue generado automáticamente por {app_name}. No es necesario responder a este mensaje.</p>',
                ]
            ],
            'Reply Mail To Creator' => [
                'subject' => 'Nuevo Mensaje',
                'variables' => '{
                    "App Name" : "app_name",
                    "Company Name" : "company_name",
                    "App Url" : "app_url",
                    "Ticket Name" : "ticket_name",
                    "Ticket Id" : "ticket_id",
                    "Ticket Description" : "ticket_description",
                    "Ticket Subject" : "ticket_subject",
                    "Ticket Reply Description" : "ticket_reply_description",
                    "Resolution Agent Name" : "resolution_agent_name",
                    "Support Agent Name" : "support_agent_name",
                    "Ticket URL" : "ticket_url"
                }',
                'lang' => [
                    'en' => '<p>Hola estimado(a) {support_agent_name}, tiene un nuevo mensaje:</p>
                            <p><b>🔹 Asunto del ticket</b>: {ticket_subject}</p>
                            <p><b>🔹 Número del ticket</b>: {ticket_id}</p>
                            <p><b>🔹 Mensaje</b>: {ticket_reply_description}</p>
                            <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Open Ticket</a></p>
                            <p><b>URL de la aplicación</b>: {app_url}</p>
                            <p><i>Este correo ha sido generado automáticamente por el sistema de {app_name}. No es necesario responder a este mensaje.</i></p>',
                    'es' => '<p>Hola estimado(a) {support_agent_name}, tiene un nuevo mensaje:</p>
                            <p><b>🔹 Asunto del ticket</b>: {ticket_subject}</p>
                            <p><b>🔹 Número del ticket</b>: {ticket_id}</p>
                            <p><b>🔹 Mensaje</b>: {ticket_reply_description}</p>
                            <p><a href="{ticket_url}" style="background-color: #2d3748; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Abrir Ticket</a></p>
                            <p><b>URL de la aplicación</b>: {app_url}</p>
                            <p><i>Este correo ha sido generado automáticamente por el sistema de {app_name}. No es necesario responder a este mensaje.</i></p>'
                ]
            ],
        ];



        foreach ($emailTemplates as $emailTemplate => $action) {
            $ntfy = NotificationTemplates::where('action', $action)->where('type', 'mail')->where('module', 'General')->count();
            if ($ntfy == 0) {
                $new = new NotificationTemplates();
                $new->action = $action;
                $new->module = 'General';
                $new->type = 'mail';
                $new->from = 'Hubi Tickets';
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

    }
}
