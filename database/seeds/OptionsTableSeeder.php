<?php

declare(strict_types=1);

use App\Models\Option;
use App\Models\OptionParameter;
use App\Models\SystemOptionValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Faq;

class OptionsTableSeeder extends Seeder
{
    use \App\Database\Seeds\CommonDatabaseSeederTrait;

    private function data()
    {
        return [
            [
                'name' => Option::NAME_PRIVACY,
                'title' => 'Privacy Policy',
                'type' => Option::TYPE_MARKDOWN,
                'value' => "At we collect and manage user data according to the following Privacy Policy.

**Data Collected**
We collect information you provide directly to us. For example, we collect information when you create an account, subscribe, participate in any interactive features of our services, fill out a form, request customer support or otherwise communicate with us. The types of information we may collect include your name, email address, postal address, credit card information and other contact or identifying information you choose to provide.

We collect anonymous data from every visitor of the Website to monitor traffic and fix bugs. For example, we collect information like web requests, the data sent in response to such requests, the Internet Protocol address, the browser type, the browser language, and a timestamp for the request.

We also use various technologies to collect information, and this may include sending cookies to your computer. Cookies are small data files stored on your hard drive or in your device memory that helps us to improve our services and your experience, see which areas and features of our services are popular and count visits. We may also collect information using web beacons (also known as \"tracking pixels\"). Web beacons are electronic images that may be used in our services or emails and to track count visits or understand usage and campaign effectiveness.

**Use of the Data**
We only use your personal information to provide you the services or to communicate with you about the Website or the services.

We employ industry standard techniques to protect against unauthorized access of data about you that we store, including personal information.

We do not share personal information you have provided to us without your consent, unless:

Doing so is appropriate to carry out your own request
We believe it's needed to enforce our legal agreements or that is legally required
We believe it's needed to detect, prevent or address fraud, security or technical issues
Sharing of Data
We don't share your personal information with third parties. Aggregated, anonymized data is periodically transmitted to external services to help us improve the Website and service.

We may allow third parties to provide analytics services. These third parties may use cookies, web beacons and other technologies to collect information about your use of the services and other websites, including your IP address, web browser, pages viewed, time spent on pages, links clicked and conversion information.

We also use social buttons provided by services like Twitter, Google+, LinkedIn and Facebook. Your use of these third party services is entirely optional. We are not responsible for the privacy policies and/or practices of these third party services, and you are responsible for reading and understanding those third party services' privacy policies.

**Opt-Out, Communication Preferences**
You may modify your communication preferences and/or opt-out from specific communications at any time. Please specify and adjust your preferences.

**Security**
We take reasonable steps to protect personally identifiable information from loss, misuse, and unauthorized access, disclosure, alteration, or destruction. But, you should keep in mind that no Internet transmission is ever completely secure or error-free. In particular, email sent to or from the Sites may not be secure.

**About Children**
The Website is not intended for children under the age of 13. We do not knowingly collect personally identifiable information via the Website from visitors in this age group.

**Changes to the Privacy Policy**
We may amend this Privacy Policy from time to time. This Privacy Policy was created by privacy-policy-template.com for MyWebsite.com. Use of information we collect now is subject to the Privacy Policy in effect at the time such information is used.

If we make major changes in the way we collect or use information, we will notify you by posting an announcement on the Website or sending you an email.",
            ], [
                'name' => Option::NAME_TERMS,
                'title' => 'Terms',
                'type' => Option::TYPE_MARKDOWN,
                'value' => "Terms of Service",
            ], [
                'name' => Option::NAME_CONTACTS,
                'title' => 'Contacts',
                'type' => Option::TYPE_MARKDOWN,
                'value' => "Contacts",
            ], [
                'name' => Option::NAME_SOCIAL_FACEBOOK,
                'title' => 'Facebook',
                'type' => Option::TYPE_TEXT,
                'value' => "https://www.facebook.com/",
            ], [
                'name' => Option::NAME_SOCIAL_TWITTER,
                'title' => 'Twitter',
                'type' => Option::TYPE_TEXT,
                'value' => "https://twitter.com/",
            ], [
                'name' => Option::NAME_SOCIAL_INSTAGRAM,
                'title' => 'Instagram',
                'type' => Option::TYPE_TEXT,
                'value' => "https://www.instagram.com/",
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->before(Option::class);
        $this->before(OptionParameter::class);
        $this->before(SystemOptionValue::class);

        foreach ($this->data() as $data) {
            /** @var Option $oOption */
            $oOption = factory(Option::class)->create([
                'purpose' => Option::PURPOSE_SYSTEM,
                'name' => $data['name'],
                'title' => $data['title'],
                'type' => $data['type'],
                'priority' => 100,
                'status' => Option::STATUS_ACTIVE,
            ]);
            SystemOptionValue::create([
                'option_id' => $oOption->id,
                'value' => $data['value'],
            ]);
        }

        $this->after();
    }
}
