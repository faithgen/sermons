<?php

namespace FaithGen\Sermons\Http\Controllers;

use Illuminate\Http\Request;
use FaithGen\Sermons\Models\Sermon;
use FaithGen\Sermons\SermonService;
use App\Http\Controllers\Controller;
use FaithGen\Sermons\Events\Created;
use FaithGen\SDK\Helpers\CommentHelper;
use FaithGen\Sermons\Http\Requests\GetRequest;
use FaithGen\Sermons\Http\Requests\IndexRequest;
use FaithGen\Sermons\Http\Requests\CreateRequest;
use FaithGen\Sermons\Http\Requests\UpdateRequest;
use FaithGen\Sermons\Http\Requests\CommentRequest;
use FaithGen\Sermons\Http\Requests\UpdatePictureRequest;
use FaithGen\Sermons\Http\Resources\Sermon as SermonResource;
use FaithGen\Sermons\Http\Resources\SermonList as ListResource;

class SermonController extends Controller
{
    /**
     * @var SermonService
     */
    private $sermonService;

    public function __construct(SermonService $sermonService)
    {
        $this->sermonService = $sermonService;
    }

    function create(CreateRequest $request)
    {
        return $this->sermonService->createFromRelationship($request->validated());
    }

    function index(IndexRequest $request)
    {
        $sermons = auth()->user()->sermons()
            ->where('preacher', 'LIKE', '%' . $request->filter_text . '%')
            ->orWhere('title', 'LIKE', '%' . $request->filter_text . '%')
            ->orWhere('preacher', 'LIKE', '%' . $request->filter_text . '%')
            ->latest()->paginate($request->has('limit') ? $request->limit : 15);
        if ($request->has('full_sermons'))
            return SermonResource::collection($sermons);
        else
            return ListResource::collection($sermons);
    }

    function view(Sermon $sermon)
    {
        $this->authorize('sermon.view', $sermon);
        SermonResource::withoutWrapping();
        return new SermonResource($sermon);
    }

    function updatePicture(UpdatePictureRequest $request)
    {
        if ($this->sermonService->getSermon()->image()->exists()) {
            try {
                $this->sermonService->deleteFiles($this->sermonService->getSermon());
            } catch (\Exception $e) { }
        }

        if ($request->hasImage && $request->has('image'))
            try {
                event(new Created($this->sermonService->getSermon()));
                return $this->successResponse('Preacher image updated successfully!');
            } catch (\Exception $e) {
                abort(500, $e->getMessage());
            } else {
            try {
                $this->sermonService->getSermon()->image()->delete();
                return $this->successResponse('Preacher image deleted successfully!');
            } catch (\Exception $e) {
                abort(500, $e->getMessage());
            }
        }
    }

    function delete(GetRequest $request)
    {
        $this->authorize('sermon.delete', $this->sermonService->getSermon());
        return $this->sermonService->destroy('Sermon deleted');
    }

    function update(UpdateRequest $request)
    {
        return $this->sermonService->update($request->validated(), 'Sermon updated successfully');
    }

    public function comment(CommentRequest $request)
    {
        return CommentHelper::createComment($this->sermonService->getSermon(), $request);
    }

    public function comments(Request $request, Sermon $sermon)
    {
        $this->authorize('sermon.view', $sermon);
        return CommentHelper::getComments($sermon, $request);
    }
}
