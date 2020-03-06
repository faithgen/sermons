<?php

namespace FaithGen\Sermons\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use FaithGen\Sermons\Jobs\S3Upload;
use FaithGen\Sermons\Models\Sermon;
use FaithGen\Sermons\SermonService;
use FaithGen\Sermons\Jobs\UploadImage;
use FaithGen\SDK\Helpers\CommentHelper;
use FaithGen\Sermons\Jobs\ProcessImage;
use FaithGen\Sermons\Jobs\MessageFollowers;
use InnoFlash\LaraStart\Traits\APIResponses;
use Illuminate\Foundation\Bus\DispatchesJobs;
use FaithGen\Sermons\Http\Requests\GetRequest;
use FaithGen\Sermons\Http\Requests\IndexRequest;
use FaithGen\Sermons\Http\Requests\CreateRequest;
use FaithGen\Sermons\Http\Requests\UpdateRequest;
use FaithGen\Sermons\Http\Requests\CommentRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use FaithGen\Sermons\Http\Requests\UpdatePictureRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use FaithGen\Sermons\Http\Resources\Sermon as SermonResource;
use FaithGen\Sermons\Http\Resources\SermonList as ListResource;

class SermonController extends Controller
{
    use AuthorizesRequests, ValidatesRequests, APIResponses, DispatchesJobs;
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
        return $this->sermonService->createFromParent($request->validated());
    }

    function index(IndexRequest $request)
    {
        $sermons = auth()->user()->sermons()
            ->where(function ($sermon) use ($request) {
                return $sermon->where('preacher', 'LIKE', '%' . $request->filter_text . '%')
                    ->orWhere('title', 'LIKE', '%' . $request->filter_text . '%')
                    ->orWhere('preacher', 'LIKE', '%' . $request->filter_text . '%');
            })
            ->latest()->paginate($request->has('limit') ? $request->limit : 15);

	SermonResource::wrap('sermons');

        if ($request->has('full_sermons'))
            return SermonResource::collection($sermons);
        else
            return ListResource::collection($sermons);
    }

    function view(Sermon $sermon)
    {
        $this->authorize('view', $sermon);
        SermonResource::withoutWrapping();
        return new SermonResource($sermon);
    }

    function updatePicture(UpdatePictureRequest $request)
    {
        if ($this->sermonService->getSermon()->image()->exists()) {
            try {
                $this->sermonService->deleteFiles($this->sermonService->getSermon());
            } catch (\Exception $e) {
            }
        }

        if ($request->hasImage && $request->has('image'))
            try {
                MessageFollowers::withChain([
                    new UploadImage($this->sermonService->getSermon(), request('image')),
                    new ProcessImage($this->sermonService->getSermon()),
                    new S3Upload($this->sermonService->getSermon())
                ])->dispatch($this->sermonService->getSermon());
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
        $this->authorize('delete', $this->sermonService->getSermon());
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
        $this->authorize('view', $sermon);
        return CommentHelper::getComments($sermon, $request);
    }
}
