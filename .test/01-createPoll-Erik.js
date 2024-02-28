// carreguem les llibreries
const { BaseTest } = require("./BasePhpTest.js")
const { By, until, Key, Select } = require("selenium-webdriver");
const assert = require('assert');
const { elementsLocated } = require("selenium-webdriver/lib/until.js");

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

        await this.driver.get("http://localhost:8080");
        let register_button = await this.driver.findElement(By.css("a[href='login.php']"));
        await this.driver.actions()
        .move({ origin: register_button })
        .click()
        .perform()

        let input_username = await this.driver.wait(until.elementLocated(By.css("input[name='userEmail']"), 2000))
        await input_username.sendKeys("eriktamano@gmail.com");
        let input_password = await this.driver.wait(until.elementLocated(By.css("input[name='pwd']"), 2000))
        await input_password.sendKeys("Th3rock1!");
        await input_password.sendKeys(Key.ENTER);
        let createPollButton = await this.driver.wait(until.elementLocated(By.id("createQuestion")), 2000)
        await this.driver.actions()
        .move({ origin: createPollButton })
        .click()
        .perform()
        let question_input = await this.driver.wait(until.elementLocated(By.id("question")), 10000)
        await question_input.sendKeys("Selenium Test Poll")
        await question_input.sendKeys(Key.ENTER)

        let answer_input_array = await this.driver.findElements(By.css(".answerInput"))
        for (let i = 0; i < answer_input_array.length; i++) {
            await answer_input_array[i].sendKeys("Selenium Test Answer " + i);
            if (i == answer_input_array.length-1) {
                await answer_input_array[i].sendKeys(Key.ENTER)
            }
        }

        let dateStart_input = await this.driver.wait(until.elementLocated(By.id("dateStart")), 2000)
        await dateStart_input.sendKeys("09112019")
        await dateStart_input.sendKeys(Key.ARROW_RIGHT)
        await dateStart_input.sendKeys("1111")

        let dateFinish_input = await this.driver.wait(until.elementLocated(By.id("dateFinish")), 2000)
        await dateFinish_input.sendKeys("10122020")
        await dateFinish_input.sendKeys(Key.ARROW_RIGHT)
        await dateFinish_input.sendKeys("2222")
        await dateFinish_input.sendKeys(Key.ENTER)

        let submit = await this.driver.wait(until.elementLocated(By.css("input[type='submit']")), 2000)
        await this.driver.actions()
        .move({ origin: submit })
        .click()
        .perform()


        console.log(await this.driver.wait(until.urlContains("dashboard.php"), 2000))
        console.log(await this.driver.getCurrentUrl())
        let pollCreated_dialog_closeButton = await this.driver.wait(until.elementLocated(By.css(".notification-container .close-icon")), 20)
        await this.driver.actions()
        .move({ origin: pollCreated_dialog_closeButton })
        .click()
        .perform()

        let logout = await this.driver.wait(until.elementLocated(By.id("logoutLink")), 10000)
        await this.driver.actions()
        .move({ origin: logout })
        .click()
        .perform()

        let logout_confirm = await this.driver.wait(until.elementLocated(By.id("closeSession")), 10000)
        await this.driver.actions()
        .move({ origin: logout_confirm })
        .click()
        .perform()

        await this.driver.wait(until.urlContains("index.php"), 2000)

        register_button = await this.driver.findElement(By.css("a[href='login.php']"));
        await this.driver.actions()
        .move({ origin: register_button })
        .click()
        .perform()

        input_username = await this.driver.wait(until.elementLocated(By.css("input[name='userEmail']"), 2000))
        await input_username.sendKeys("etamanosantos.cf@iesesteveterradas.cat");
        input_password = await this.driver.wait(until.elementLocated(By.css("input[name='pwd']"), 2000))
        await input_password.sendKeys("Th3rock1!");
        await input_password.sendKeys(Key.ENTER);

        let listPolls_button = await this.driver.wait(until.elementLocated(By.css("a[href='list_polls.php']")), 10000);
        await this.driver.actions()
        .move({ origin: listPolls_button })
        .click()
        .perform()

        await this.driver.wait(until.urlContains("list_polls.php"), 10000)
        try {
            let pollNameElement = await this.driver.findElement(By.css("pollItem:last-child .nameQuestionPollItem"))
            if (pollNameElement) {
                assert(pollNameElement.getText() == "Selenium Test Poll", "ERROR TEST: la enquesta es mostra a la llista d'enquestes d'un altre usuari")
            }
        } catch (error) {
        }

        console.log("TEST OK");
	}
}

// executem el test

(async function test_example() {
	const test = new MyTest();
	await test.run();
	console.log("END")
})();