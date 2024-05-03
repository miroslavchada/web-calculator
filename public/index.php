<?php
    require_once dirname(__DIR__) . "/vendor/autoload.php";
    
    use Sterzik\Expression\Parser;

    function evaluateExpression(string $expression) : array {
        $error = null;
        $value = null;

        if ($expression !== '') {
            try {
                $parser = new Parser();
                $parser->throwExceptions(true);
                $value = $parser->parse($expression)->evaluate();

                if (!is_numeric($value)) {
                    $value = null;
                    $error = "Výsledek není číslo";
                }
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }
        }

        return [
            "value" => $value,
            "error" => $error,
        ];
    }

    $expression = $_POST["expression"] ?? '';
    if (!is_string($expression)) {
        $expression = "";
    }

    $final = evaluateExpression($expression);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulačka Chada I3</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form method="post">
            <input type="text" name="expression" value="<?php echo $expression; ?>" placeholder="Napište výraz">
            <input type="submit" value="Vyhodnotit">
        </form>
        <p id="result" class="<?php echo isset($final["error"]) ? 'wrong' : ''; ?>">
            <?php if($final['value'] !== null): ?>
                <?php echo $final["value"]; ?>
            <?php endif ?>

            <?php if($final['error'] !== null): ?>
                <?php echo $final["error"]; ?>
            <?php endif ?>
        </p>
    </div>
</body>
</html>