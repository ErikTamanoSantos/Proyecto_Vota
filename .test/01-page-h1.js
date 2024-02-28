// carreguem les llibreries
const { BaseTest } = require("./BasePhpTest.js")
const { By, until, Key, Select } = require("selenium-webdriver");
const assert = require('assert');

// heredem una classe amb un sol mètode test()
// emprem this.driver per utilitzar Selenium

class MyTest extends BaseTest
{
	async test() {
        // testejem LOGIN CORRECTE usuari predefinit
        //////////////////////////////////////////////////////
        /*await this.driver.get("http://localhost:8000/browser/www/");
        await this.driver.findElement(By.id("usuari")).sendKeys("ieti");
        await this.driver.findElement(By.id("contrasenya")).sendKeys("cordova");
        await this.driver.findElement(By.xpath("//button[text()='Login']")).click();

        // comprovem que l'alert message és correcte
        await this.driver.wait(until.alertIsPresent(),2000,"ERROR TEST: després del login ha d'aparèixer un alert amb el resultat de la validació de la contrasenya.");
        let alert = await this.driver.switchTo().alert();
        let alertText = await alert.getText();
        let assertMessage = "Login exitós";
        assert(alertText==assertMessage,"ERROR TEST: l'usuari ieti/cordova hauria d'entrar amb el missatge '"+assertMessage+"' en un alert.");
        await alert.accept();
        */

        // Inicialitzar servidor PHP i anar a la pagina register
        await this.driver.get("http://localhost:8080");
        var register_button = await this.driver.findElement(By.css("a[href='register.php']"));
        await this.driver.actions()
        .move({ origin: register_button })
        .click()
        .perform()

        // Comprova que existeixen els inputs de register i inserta les dades corresponents
        let input_username = await this.driver.wait(until.elementLocated(By.id("username"), 20))
        assert(input_username, "ERROR TEST: input 'username' no trobat")
        await input_username.sendKeys("Selenium Tester");
        await input_username.sendKeys(Key.ENTER);

        let input_password = await this.driver.wait(until.elementLocated(By.id("password"), 20))
        assert(input_password, "ERROR TEST: input 'password' no trobat")
        await input_password.sendKeys("Aa123456789!");
        await input_password.sendKeys(Key.TAB);

        let input_password_confirm = await this.driver.wait(until.elementLocated(By.id("password2"), 20))
        assert(input_password_confirm, "ERROR TEST: input 'confirm password' no trobat")
        await input_password_confirm.sendKeys("Aa123456789!");
        await input_password.sendKeys(Key.ENTER);

        let input_email = await this.driver.wait(until.elementLocated(By.id("email")), 20)
        assert(input_email, "ERROR TEST: input 'email' no trobat")
        await input_email.sendKeys("selenium@test.com")
        await input_email.sendKeys(Key.ENTER)

        let input_phone = await this.driver.wait(until.elementLocated(By.id("phone")), 20)
        assert(input_phone, "ERROR TEST: input 'phone number' no trobat")
        await input_phone.sendKeys("+34123456789")
        await input_phone.sendKeys(Key.ENTER)

        let input_country = new Select(await this.driver.wait(until.elementLocated(By.id("country"))), 20)
        assert(input_country, "ERROR TEST: select 'country' no trobat")
        await input_country.selectByVisibleText("España")

        let input_city = await this.driver.wait(until.elementLocated(By.id("city")), 20)
        assert(input_city, "ERROR TEST: input 'city' no trobat")
        await input_city.sendKeys("Cornellà de Llobregat")
        await input_city.sendKeys(Key.ENTER)

        let input_postalCode = await this.driver.wait(until.elementLocated(By.id("postalCode")), 20)
        assert(input_postalCode, "ERROR TEST: input 'postal code' no trobat")
        await input_postalCode.sendKeys("08940")
        await input_postalCode.sendKeys(Key.ENTER)

        // Fa el submit i busca el popup de registre completat al recarregar la pagina
        let submit = await this.driver.wait(until.elementLocated(By.id("registerSubmit")), 20)
        assert(submit, "ERROR TEST: botó submit no trobat")
        await this.driver.actions()
        .move({ origin: submit})
        .click()
        .perform()
        let notificationSuccess = false
        try {
            let success_dialog = await this.driver.wait(until.elementLocated(By.css(".notification-container:last-child .success")), 20)
            if (success_dialog) {
                notificationSuccess = true
            }
        } catch (error) {}
        assert(notificationSuccess, "ERROR TEST: register no completat")
        console.log("TEST OK");

        
	}
}

// executem el test

(async function test_example() {
	const test = new MyTest();
	await test.run();
	console.log("END")
})();