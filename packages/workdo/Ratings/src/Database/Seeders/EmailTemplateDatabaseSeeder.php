<?php

namespace Workdo\Ratings\Database\Seeders;

use App\Models\NotificationTemplateLangs;
use App\Models\NotificationTemplates;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class EmailTemplateDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $emailTemplates = [
            'Ticket Rating',
        ];

        $defaultTemplate = [
            'Ticket Rating' => [
                'subject' => 'Ticket Rating',
                'variables' => '{
                "App Name": "app_name",
                "Company Name ":"company_name",
                "Customer Name": "customer_name",
                "User Name": "user_name",
                "Rating Url": "rating_url"
            }',
                'lang' => [
                    'ar' => '<p>عزيزي {customer_name},</p>
                                <p>شكرا لاختيارك {company_name}. نأمل أن تكون راضيًا عن الدعم الذي قدمناه لك {user_name}.</p>
                                <p><strong>يرجى ترك تعليقك هنا:</strong> {rating_url}</p>
                                <p>شكرا لك، ونحن نتطلع إلى مساعدتك مرة أخرى في <strong>{app_name}</strong>.
                            </p>',
                    'da' => '<p>Kære {customer_name},</p>
                                <p>Tak fordi du valgte {company_name}. Vi håber, at du var tilfreds med den støtte, som vi tilbyder {user_name}.</p>
                                <p><strong>Skriv venligst din feedback her:</strong> {rating_url}</p>
                                <p>Tak, og vi ser frem til at hjælpe dig igen kl <strong>{app_name}</strong>.
                            </p>',
                    'de' => '<p>Liebling {customer_name},</p>
                                <p>Vielen Dank für Ihre Wahl {company_name}. Wir hoffen, Sie waren mit der Unterstützung durch zufrieden {user_name}.</p>
                                <p><strong>Hinterlassen Sie hier Ihr Feedback:</strong> {rating_url}</p>
                                <p>Vielen Dank und wir freuen uns, Ihnen wieder behilflich zu sein. <strong>{app_name}</strong>.
                            </p>',
                    'en' => '<p>Dear {customer_name},</p>
                                <p>Thank you for choosing {company_name}. We hope you were satisfied with the support provided by {user_name}.</p>
                                <p><strong>Please leave your feedback here:</strong> {rating_url}</p>
                                <p>Thank you, and we look forward to assisting you again at <strong>{app_name}</strong>.
                            </p>',
                    'es' => '<p>Estimada {customer_name},</p>
                                <p>Gracias por elegirnos {company_name}. Esperamos que haya quedado satisfecho con el soporte brindado por {user_name}.</p>
                                <p><strong>Por favor, deja tu opinión aquí:</strong> {rating_url}</p>
                                <p>Gracias y esperamos poder ayudarle nuevamente en <strong>{app_name}</strong>.
                            </p>',
                    'fr' => '<p>Chère {customer_name},</p>
                                <p>Merci davoir choisi {company_name}. Nous espérons que vous avez été satisfait du support fourni par {user_name}.</p>
                                <p><strong>Sil vous plaît laissez vos commentaires ici:</strong> {rating_url}</p>
                                <p>Merci et nous espérons pouvoir vous aider à nouveau à <strong>{app_name}</strong>.
                            </p>',
                    'it' => '<p>Cara {customer_name},</p>
                                <p>Grazie per aver scelto {company_name}. Ci auguriamo che tu sia rimasto soddisfatto del supporto fornito da {user_name}.</p>
                                <p><strong>Per favore lascia il tuo feedback qui:</strong> {rating_url}</p>
                                <p>Grazie e non vediamo lora di assisterti di nuovo a <strong>{app_name}</strong>.
                            </p>',
                    'ja' => '<p>親愛なる {customer_name},</p>
                                <p>お選びいただきありがとうございます {company_name}. ご満足いただけたでしょうか？ {user_name}.</p>
                                <p><strong>フィードバックをこちらに残してください:</strong> {rating_url}</p>
                                <p>ありがとうございました。またのご利用をお待ちしております。 <strong>{app_name}</strong>.
                            </p>',
                    'nl' => '<p>Beste {customer_name},</p>
                                <p>Bedankt voor uw keuze {company_name}. Wij hopen dat u tevreden bent met de ondersteuning die wij u bieden. {user_name}.</p>
                                <p><strong>Laat hier uw feedback achter:</strong> {rating_url}</p>
                                <p>Bedankt, en we kijken ernaar uit u weer te mogen helpen. <strong>{app_name}</strong>.
                            </p>',
                    'pl' => '<p>Droga {customer_name},</p>
                                <p>Dziękujemy za wybór {company_name}. Mamy nadzieję, że byli Państwo zadowoleni ze wsparcia, jakie otrzymali Państwo od nas. {user_name}.</p>
                                <p><strong>Proszę zostawić swoją opinię tutaj:</strong> {rating_url}</p>
                                <p>Dziękujemy i mamy nadzieję, że będziemy mogli ponownie Państwu pomóc. <strong>{app_name}</strong>.
                            </p>',
                    'ru' => '<p>Дорогой {customer_name},</p>
                                <p>Спасибо за выбор {company_name}. Мы надеемся, что вы остались довольны оказанной поддержкой. {user_name}.</p>
                                <p><strong>Пожалуйста, оставьте свой отзыв здесь:</strong> {rating_url}</p>
                                <p>Спасибо, и мы будем рады помочь вам снова. <strong>{app_name}</strong>.
                            </p>',
                    'pt' => '<p>Querida {customer_name},</p>
                                <p>Obrigado por escolher {company_name}. Esperamos que tenha ficado satisfeito com o apoio prestado pela {user_name}.</p>
                                <p><strong>Por favor, deixe aqui o seu feedback:</strong> {rating_url}</p>
                                <p>Obrigado e esperamos poder ajudá-lo novamente em <strong>{app_name}</strong>.
                            </p>',
                    'tr' => '<p>Canım {customer_name},</p>
                                <p>Seçtiğiniz için teşekkür ederiz {company_name}. Umarız bize sağladığınız destekten memnun kalmışsınızdır. {user_name}.</p>
                                <p><strong>Lütfen geri bildiriminizi buraya bırakın:</strong> {rating_url}</p>
                                <p>Teşekkür ederiz ve size tekrar yardımcı olmayı dört gözle bekliyoruz. <strong>{app_name}</strong>.
                            </p>',                    
                    'he' => '<p>יָקָר {customer_name},</p>
                            <p>תודה שבחרתם {company_name}. אנו מקווים שהיית מרוצה מהתמיכה שסיפקת {user_name}.</p>
                            <p><strong>אנא השאר את המשוב שלך כאן:</strong> {rating_url}</p>
                            <p>תודה לך, ואנו מצפים לסייע לך שוב ב <strong>{app_name}</strong>.
                            </p>',
                    'zh' => '<p>亲爱的 {customer_name},</p>
                                <p>感谢您的选择 {company_name}. 我们希望您对提供的支持感到满意 {user_name}.</p>
                                <p><strong>请在此处留下您的反馈:</strong> {rating_url}</p>
                                <p>谢谢您，我们期待再次为您提供帮助 <strong>{app_name}</strong>.
                            </p>',
                    'pt-br' => '<p>Querida {customer_name},</p>
                                <p>Obrigado por escolher {company_name}. Esperamos que você tenha ficado satisfeito com o suporte fornecido por {user_name}.</p>
                                <p><strong>Por favor, deixe seu feedback aqui:</strong> {rating_url}</p>
                                <p>Obrigado e esperamos poder ajudá-lo novamente em <strong>{app_name}</strong>.
                            </p>',
                ],
            ],
        ];

        foreach ($emailTemplates as $emailTemplate => $action) {
            $ntfy = NotificationTemplates::where('action', $action)->where('type', 'mail')->where('module', 'Ratings')->count();
            if ($ntfy == 0) {
                $new = new NotificationTemplates();
                $new->action = $action;
                $new->module = 'Ratings';
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
    }
}
