# 11 Rules Overview

## PresentationToControllerConfigRector

Moves configuration from Presentation to Communication tests.

- class: [`SprykerSdk\Architector\Codeception\PresentationToControllerConfig\PresentationToControllerConfigRector`](../src/SprykerSdk/Architector/Codeception/PresentationToControllerConfig/PresentationToControllerConfigRector.php)

```diff
 suites:
-    Presentation:
-        path: Presentation
-        class_name: XyPresentationTester
+    Communication:
+        path: Communication
+        class_name: XyCommunicationTester
```

<br>

## PresentationToControllerTestClassContFetchRector

Replace usages of XPresentationTester::const with XControllerTester::const

- class: [`SprykerSdk\Architector\Codeception\PresentationToControllerTest\PresentationToControllerTestClassContFetchRector`](../src/SprykerSdk/Architector/Codeception/PresentationToControllerTest/PresentationToControllerTestClassContFetchRector.php)

```diff
-$foo = XPresentationTester::const;
+$foo = XControllerTester::const;
```

<br>

## PresentationToControllerTestFileMoveRector

Moves Presentation test files to Controller directory

- class: [`SprykerSdk\Architector\Codeception\PresentationToControllerTest\PresentationToControllerTestFileMoveRector`](../src/SprykerSdk/Architector/Codeception/PresentationToControllerTest/PresentationToControllerTestFileMoveRector.php)

```diff
-// file: tests/Presentation/SomeTest.php
-namespace Foo\Presentation;
+// file: tests/Controller/SomeTest.php
+namespace Foo\Controller;
 class SomeTest
 {
 }
```

<br>

## PresentationToControllerTestMethodParamRector

Refactors method arguments and doc blocks from using XPresentationTester to XControllerTester

- class: [`SprykerSdk\Architector\Codeception\PresentationToControllerTest\PresentationToControllerTestMethodParamRector`](../src/SprykerSdk/Architector/Codeception/PresentationToControllerTest/PresentationToControllerTestMethodParamRector.php)

```diff
 namespace Foo\Presentation;
 class SomeTest
 {
     /**
-     * @param XPresentationTester $i
+     * @param XControllerTester $i
      *
      * @return void
      */
-    public function test(XPresentationTester $i) {}
+    public function test(XControllerTester $i) {}
 }
```

<br>

## PresentationToControllerTestNamespaceRector

Refactors namespace from Presentation to Controller

- class: [`SprykerSdk\Architector\Codeception\PresentationToControllerTest\PresentationToControllerTestNamespaceRector`](../src/SprykerSdk/Architector/Codeception/PresentationToControllerTest/PresentationToControllerTestNamespaceRector.php)

```diff
-namespace Foo\Presentation;
+namespace Foo\Controller;

 class SomeTest
 {
 }
```

<br>

## PresentationToControllerTesterClassNameRector

Renames PresentationTester to ControllerTester

- class: [`SprykerSdk\Architector\Codeception\PresentationToControllerTester\PresentationToControllerTesterClassNameRector`](../src/SprykerSdk/Architector/Codeception/PresentationToControllerTester/PresentationToControllerTesterClassNameRector.php)

```diff
-namespace Foo\Presentation;
-class SomePresentationTester
+namespace Foo\Controller;
+class SomeControllerTester
 {
 }
```

<br>

## PresentationToControllerTesterFileMoveRector

Moves Presentation test to Controller test suite namespace

- class: [`SprykerSdk\Architector\Codeception\PresentationToControllerTester\PresentationToControllerTesterFileMoveRector`](../src/SprykerSdk/Architector/Codeception/PresentationToControllerTester/PresentationToControllerTesterFileMoveRector.php)

```diff
-// file: tests/Presentation/SomeTest.php
-namespace Foo\Presentation;
-class SomePresentationTest
+// file: tests/Controller/SomeTest.php
+namespace Foo\Controller;
+class SomeTest
 {
 }
```

<br>

## PresentationToControllerTesterTraitUseRector

Renames trait use from Presentation to Controller namespace

- class: [`SprykerSdk\Architector\Codeception\PresentationToControllerTester\PresentationToControllerTesterTraitUseRector`](../src/SprykerSdk/Architector/Codeception/PresentationToControllerTester/PresentationToControllerTesterTraitUseRector.php)

```diff
 namespace Foo\Presentation;
 class SomeTest
 {
-    use _generated\XPresentationTesterActions;
+    use _generated\XControllerTesterActions;
 }
```

<br>

## RemoveInitialTesterCommentRector

Removes the initial comment in tester classes.

- class: [`SprykerSdk\Architector\Codeception\RemoveInitialTesterComment\RemoveInitialTesterCommentRector`](../src/SprykerSdk/Architector/Codeception/RemoveInitialTesterComment/RemoveInitialTesterCommentRector.php)

```diff
 class SomeTest
 {
     use _generated\XPresentationTesterActions;
-
-    /**
-     * Define custom actions here
-     */
 }
```

<br>

## RenameParamToMatchTypeRector

Rename param to match ClassType

- class: [`SprykerSdk\Architector\Rename\RenameParamToMatchTypeRector`](../src/SprykerSdk/Architector/Rename/RenameParamToMatchTypeRector.php)

```diff
 class SomeClass
 {
-    public function run(FooBarTransfer $fooBar)
+    public function run(FooBarTransfer $fooBarTransfer)
     {
-        $foo = $fooBar;
+        $foo = $fooBarTransfer;
     }
 }
```

<br>

```diff
 class SomeClass
 {
-    public function run(SpyFooBar $fooBar)
+    public function run(SpyFooBar $fooBarEntity)
     {
-        $foo = $fooBar;
+        $foo = $fooBarEntity;
     }
 }
```

<br>

```diff
 class SomeClass
 {
-    public function run(SpyFooBarQuery $fooBar)
+    public function run(SpyFooBarQuery $fooBarQuery)
     {
-        $foo = $fooBar;
+        $foo = $fooBarQuery;
     }
 }
```

<br>

## TriggerErrorMessagesWithSprykerPrefixRector

Refactors trigger_error calls to ensure the passed message contains "Spryker: " as prefix.

- class: [`SprykerSdk\Architector\TriggerError\TriggerErrorMessagesWithSprykerPrefixRector`](../src/SprykerSdk/Architector/TriggerError/TriggerErrorMessagesWithSprykerPrefixRector.php)

```diff
-trigger_error('My message', E_USER_DEPRECATED);
+trigger_error('Spryker: My message', E_USER_DEPRECATED);
```

<br>

```diff
-$message = 'Foo';
+$message = 'Spryker: Foo';
 trigger_error($message, E_USER_DEPRECATED);
```

<br>

```diff
-$message = 'Foo' . 'Bar';
+$message = 'Spryker: Foo' . 'Bar';
 trigger_error($message, E_USER_DEPRECATED);
```

<br>

```diff
-$message = 'Foo' . 'Bar' . 'Baz';
+$message = 'Spryker: Foo' . 'Bar' . 'Baz';
 trigger_error($message, E_USER_DEPRECATED);
```

<br>

```diff
-$message = sprintf('Foo %s', $something);
+$message = sprintf('Spryker: Foo %s', $something);
 trigger_error($message, E_USER_DEPRECATED);
```

<br>
