# Contributing to IPFS-PHP

Thank you for considering contributing to **ipfs-php**! <br />
All contributions are appreciated, whether it's bug reports, feature requests, or code improvements. <br />
The following guidelines will help you get started and ensure a smooth contribution process.

## Getting Started

1. **Fork the Repository**
    - Click the “Fork” button at the top-right corner of this repository.
    - Clone your fork locally, for example:
      ```bash
      git clone git@github.com:your-username/ipfs-php
      ```
2. **Create a Branch**
    - Create a new branch from `main` (or the default branch) to work on your changes:
      ```bash
      git checkout -b feature/my-new-feature
      ```
    - Name your branch in a way that describes what you’re working on (e.g., `feature/add-transaction-support` or `bugfix/fix-transaction-deserialization`).

3. **Make Changes**
    - Implement your changes, whether it’s a bug fix, feature enhancement, or documentation update.
    - Push your branch to your GitHub fork:
      ```bash
      git push origin feature/my-new-feature
      ```

4. **Open a Pull Request (PR)**
    - Go to your fork on GitHub and click on the “Compare & pull request” button for your new branch.
    - Provide a clear description of the changes you’ve made.
    - Note that any PR that does not have the Continuous Integration (CI) checks passing (linting, static analysis, tests) will **not** be reviewed.

## Code Standards

I aim to maintain clean, consistent, and readable code throughout `ipfs-php`. Before opening a PR, please ensure your changes conform to these standards:

- **Linting**: `php-cs-fixer` with PSR-12 to enforce coding standards.
- **Static Analysis**: `phpstan` to improve code quality.
- **Formatting**: Code should be formatted to be consistent with the project style.

## Testing

1. **Write Tests**
    - When adding new features or fixing bugs, include test coverage to prove that your changes work as expected and do not break existing functionality.
    - We use [PHPUnit](https://phpunit.de/) for testing. Place your tests in the appropriate directory under `tests/`.

2. **Running Tests Locally**
    - Ensure all existing and new tests pass before submitting a PR:
      ```bash
      make test
      ```
    - Make sure tests are passing and coverage is acceptable.

## Linting & Static Analysis

1. **Linting**
    - Run `php-cs-fixer` to catch syntax or formatting issues:
      ```bash
      make phpcs
      ```
2. **Static Analysis**
    - Run `phpstan` to catch potential bugs or incorrect assumptions:
      ```bash
      make phpstan
      ```

Any Pull Request that does not pass **all** of these checks (linting, static analysis, and tests) in the CI pipeline will not be reviewed.

## Pull Request Guidelines

- Keep changes focused: Avoid bundling multiple, unrelated changes in a single PR.
- Provide a clear description and summary of what the changes do.
- Reference any relevant issue numbers (e.g., “Closes #123” if it fixes an issue).
- Be responsive to feedback.

## Community and Feedback

- If you’re unsure about the approach or want to discuss changes before making them, please [open an issue](./issues) to start a conversation.
- Respectful communication is expected. We aim to foster a positive, collaborative environment.

---

Thank you again for your interest in contributing to **ipfs-php**! <br />
Your time and effort in improving the project are greatly appreciated. <br />
Together, we can build and maintain a robust and reliable library for the IPFS ecosystem.
