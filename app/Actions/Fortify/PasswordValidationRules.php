<?php

namespace App\Actions\Fortify;

trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        // パスワードに8文字以上の要件を追加
        return ['required', 'string', 'min:8', 'confirmed'];
    }
}
