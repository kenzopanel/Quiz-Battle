<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class QuestionService
{
    private const QUESTIONS_STORAGE_PATH = 'questions';

    public function storeQuestion(array $data): Question
    {
        $questionData = [
            'question_text' => $data['question_text'],
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $questionData['image_path'] = $this->storeImage($data['image']);
        }

        return $data['quiz']->questions()->create($questionData);
    }

    public function updateQuestion(Question $question, array $data): void
    {
        $updateData = [
            'question_text' => $data['question_text'],
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->deleteImage($question->image_path);
            $updateData['image_path'] = $this->storeImage($data['image']);
        } elseif (array_key_exists('image', $data) && $data['image'] === null) {
            $this->deleteImage($question->image_path);
            $updateData['image_path'] = null;
        }

        $question->update($updateData);
    }

    public function deleteQuestion(Question $question): void
    {
        $this->deleteImage($question->image_path);
        $question->delete();
    }

    private function storeImage(UploadedFile $image): string
    {
        $filename = uniqid('question_', true).'.'.$image->getClientOriginalExtension();

        return Storage::disk('public')->putFileAs(self::QUESTIONS_STORAGE_PATH, $image, $filename);
    }

    private function deleteImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
