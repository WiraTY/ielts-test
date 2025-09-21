# Excel Template Documentation

## Overview

This documentation provides instructions on how to use the Excel templates for importing questions into quizzes and placement tests in the Trial Class Application.

## Available Templates

There are two Excel templates available:
1. Quiz Questions Template
2. Placement Test Questions Template

Both templates follow the same basic structure but are used in different contexts.

## Downloading Templates

### For Quiz Questions
1. Navigate to the Admin Dashboard
2. Go to "Courses" → Select a Course → Select a Lesson → Manage Quiz
3. Click "Import Questions" button
4. Click "Download Template" link

### For Placement Test Questions
1. Navigate to the Admin Dashboard
2. Go to "Placement Tests" → Select a Placement Test → View
3. Click "Import Questions" button
4. Click "Download Template" link

## Template Structure

The Excel template contains the following columns:

### Column A: Question Text
- Contains the text of the question
- Can include multiple lines and basic formatting
- Example: "What is the capital of France?"

### Column B: Option A
- Contains the first answer option (for MCQ questions)
- Example: "London"

### Column C: Option B
- Contains the second answer option (for MCQ questions)
- Example: "Berlin"

### Column D: Option C
- Contains the third answer option (for MCQ questions)
- Example: "Paris"

### Column E: Option D
- Contains the fourth answer option (for MCQ questions)
- Example: "Madrid"

### Column F: Correct Answer
- Specifies the correct answer for the question
- For MCQ questions: Enter the letter of the correct option (A, B, C, or D)
- For multi-select questions: Enter the letters of all correct options separated by commas (A,B,C)
- For essay questions: This column is not used
- Example: "C"

### Column G: Score
- Specifies the points awarded for correctly answering this question
- Must be a positive integer
- If left blank, defaults to 1
- Example: "1"

### Column H: Order
- Specifies the order in which questions should appear
- Must be a positive integer
- Lower numbers appear first
- Example: "1"

## Preparing Your Data

### Formatting Guidelines
1. Keep question text clear and concise
2. Ensure answer options are distinct and plausible
3. Double-check correct answers before importing
4. Use consistent scoring across questions
5. Set appropriate order numbers for question sequence

### Data Validation
- Question Text: Required field
- Options A-D: Required for MCQ and multi-select questions
- Correct Answer: Required for MCQ and multi-select questions, not used for essay questions
- Score: Optional, defaults to 1 if blank
- Order: Optional, defaults to question sequence if blank

### Example Data
| Question Text | Option A | Option B | Option C | Option D | Correct Answer | Score | Order |
|---------------|----------|----------|----------|----------|----------------|-------|-------|
| What is the capital of France? | London | Berlin | Paris | Madrid | C | 1 | 1 |
| Which of these are primary colors? | Red | Blue | Green | Yellow | A,B,D | 2 | 2 |

## Importing Data

### Steps to Import
1. Prepare your data in the Excel template
2. Save the file in XLSX, XLS, or CSV format
3. Navigate to the appropriate import page (quiz or placement test)
4. Click "Choose File" and select your prepared Excel file
5. Decide whether to replace existing questions:
   - Check "Replace existing questions" to delete all current questions and import only the new ones
   - Leave unchecked to add new questions to existing ones
6. Click "Import Questions"

### Import Results
- The system will display the number of questions successfully imported
- Any errors will be shown with specific details
- You can review the imported questions on the questions management page

## Troubleshooting

### Common Issues
1. **Invalid File Format**: Ensure your file is saved as XLSX, XLS, or CSV
2. **Missing Required Fields**: Check that all required columns have data
3. **Invalid Correct Answer**: Ensure correct answers match the available options
4. **Duplicate Questions**: The system will not prevent importing duplicate questions

### Error Messages
- "Failed to import questions": Check file format and content
- "Invalid correct answer": Verify that correct answers match available options
- "Invalid score": Ensure score is a positive integer
- "Invalid order": Ensure order is a positive integer

## Best Practices

1. **Backup Existing Questions**: Before importing, note the current questions in case you need to revert
2. **Test with Small Sets**: Try importing a few questions first to verify the process
3. **Use Consistent Formatting**: Keep question formatting consistent throughout
4. **Validate Data**: Double-check all data before importing
5. **Document Changes**: Keep records of what questions were imported and when