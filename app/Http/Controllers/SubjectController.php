<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class SubjectController extends AppBaseController
{
    /** @var SubjectRepository */
    private SubjectRepository $subjectRepository;

    public function __construct(SubjectRepository $subjectRepo)
    {
        $this->subjectRepository = $subjectRepo;
    }

    /**
     * Display a listing of the Subject.
     */
    public function index(Request $request)
    {
        $subjects = $this->subjectRepository->paginate(10);

        return view('subjects.index')
            ->with('subjects', $subjects);
    }

    /**
     * Show the form for creating a new Subject.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created Subject in storage.
     */
    public function store(CreateSubjectRequest $request)
    {
        $input = $request->all();

        $this->subjectRepository->create($input);

        Flash::success('Subject saved successfully.');

        return redirect(route('subjects.index'));
    }

    /**
     * Display the specified Subject.
     */
    public function show($id)
    {
        $subject = $this->subjectRepository->find($id);

        if (empty($subject)) {
            Flash::error('Subject not found');

            return redirect(route('subjects.index'));
        }

        return view('subjects.show')->with('subject', $subject);
    }

    /**
     * Show the form for editing the specified Subject.
     */
    public function edit($id)
    {
        $subject = $this->subjectRepository->find($id);

        if (empty($subject)) {
            Flash::error('Subject not found');

            return redirect(route('subjects.index'));
        }

        return view('subjects.edit')->with('subject', $subject);
    }

    /**
     * Update the specified Subject in storage.
     */
    public function update($id, UpdateSubjectRequest $request)
    {
        $subject = $this->subjectRepository->find($id);

        if (empty($subject)) {
            Flash::error('Subject not found');

            return redirect(route('subjects.index'));
        }

        $this->subjectRepository->update($request->all(), $id);

        Flash::success('Subject updated successfully.');

        return redirect(route('subjects.index'));
    }

    /**
     * Remove the specified Subject from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $subject = $this->subjectRepository->find($id);

        if (empty($subject)) {
            Flash::error('Subject not found');

            return redirect(route('subjects.index'));
        }

        $this->subjectRepository->delete($id);

        Flash::success('Subject deleted successfully.');

        return redirect(route('subjects.index'));
    }
}
